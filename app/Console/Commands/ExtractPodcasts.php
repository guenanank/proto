<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Galleries;

class ExtractPodcasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:podcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data podcast';

    private $client;
    private $uri = 'https://api.gridtechno.com/extract/';
    private $headers = [
      'Content-Type' => 'application/json',
      'Api-Token' => '$2y$10$c1V7USh1HZSr9irAuwVcpOIRoYWhE4PCPI9jh31y4KXnoq4B3DA9C'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client(['headers' => $this->headers]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Cache::forget('inPodcast');
        // return;
        $skip = Cache::get('inPodcast', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => [
            'table' => 'podcast',
            'where' => true,
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00'
          ]
        ])->getBody();

        $media = Cache::rememberForever('media:all', function() {
            return Media::withTrashed()->with('group')->get();
        });

        if ($skip >= (int) $total->getContents()) {
            Cache::forget('inPodcast');
            return;
        }

        $client = $this->client->get($this->uri . 'podcasts', [
          'query' => [
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00',
            'skip' => $skip,
            'take' => $interval,
            'order' => 'created_date'
          ]
        ])->getBody();

        foreach (json_decode($client->getContents()) as $podcast) {
            $medium = $media->where('oId', $podcast->site_id == 0 ? rand(1, $media->count()) : $podcast->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $created = Carbon::parse($podcast->created_date);

            $field['mediaId'] = $medium->id;
            $field['meta']['title'] = $podcast->name;
            $field['meta']['url'] = $podcast->url;
            $field['meta']['duration'] = $podcast->duration;
            $field['meta']['description'] = $podcast->description;
            $field['meta']['file'] = null;
            $field['meta']['cover'] = null;
            $field['creationDate'] = $created;
            if (!$podcast->status) {
                $field['removedAt'] = now();
            }

            $podcastModel = Galleries::withTrashed()->updateOrCreate(['type' => 'podcasts', 'oId' => $podcast->id], $field);
            // Storage::put($field['meta']['path'], $img->encode('jpeg'), 'public');
            Cache::forget('galleries:' . $podcastModel->id);
            Cache::forget('galleries:podcasts:all');
            // Cache::forever('galleries:' . $podcastModel->id, $podcastModel->load('media'));
            $this->line(is_null($podcastModel) ? 'empty' : sprintf('Extracted %s', $podcastModel->meta['title']));
        }

        Cache::increment('inPodcast', $interval);
    }
}
