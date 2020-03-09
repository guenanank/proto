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

class ExtractMusics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:music';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data music';

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
        // Cache::forget('inMusic');
        // return;
        $skip = Cache::get('inMusic', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => [
            'table' => 'music',
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
            Cache::forget('inMusic');
            return;
        }

        $client = $this->client->get($this->uri . 'musics', [
          'query' => [
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00',
            'skip' => $skip,
            'take' => $interval,
            'order' => 'created_date'
          ]
        ])->getBody();

        foreach (json_decode($client->getContents()) as $music) {

            $medium = $media->where('oId', 42)->first();
            if (empty($medium->group)) {
                continue;
            }
            $created = Carbon::parse($music->created_date);

            $field['mediaId'] = $medium->id;
            $field['meta']['title'] = $music->title;
            $field['meta']['artist'] = $music->artist;
            $field['meta']['album'] = $music->album;
            $field['meta']['youtubeUrl'] = $music->youtube_url;
            $field['meta']['file'] = null;
            $field['meta']['cover'] = null;
            $field['creationDate'] = $created;
            if(!$music->status) {
              $field['removedAt'] = now();

            }

            $musicModel = Galleries::withTrashed()->updateOrCreate(['type' => 'musics', 'oId' => $music->id], $field);
            // Storage::put($field['meta']['path'], $img->encode('jpeg'), 'public');
            Cache::forget('galleries:' . $musicModel->id);
            Cache::forget('galleries:musics:all');
            // Cache::forever('galleries:' . $musicModel->id, $musicModel->load('media'));
            $this->line(is_null($musicModel) ? 'empty' : sprintf('Extracted %s', $musicModel->meta['title']));
        }

        Cache::increment('inMusic', $interval);
    }
}
