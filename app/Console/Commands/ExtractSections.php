<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;

class ExtractSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:section';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data sections';

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
        // return Cache::forget('inChannel');
        $skip = Cache::get('inChannel', 0);
        $interval = 1000;
        $total = $this->client->get($this->uri . 'count', [
          'query' => ['table' => 'section']
        ])->getBody();

        $media = Cache::get('media:withTrashed', function() {
            return Media::withTrashed()->with('group')->get();
        });

        if ($skip >= (int) $total->getContents()) {
            return Cache::forget('inChannel');
        }

        $client = $this->client->get($this->uri . 'sections', [
          'query' => ['skip' => $skip, 'take' => $interval, 'order' => 'created_date']
        ])->getBody();

        foreach(json_decode($client->getContents()) as $section) {
            $medium = $media->where('oId', $section->site_id == 0 ? rand(1, $media->count()) : $section->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            if ($section->parent && $section->parent > 0) {
                $parent = Channels::withTrashed()->find($section->parent);
            }

            $field['name'] = $section->name;
            $field['slug'] = $section->alias;
            $field['sub'] = !isset($parent) && empty($parent) ? null : $parent->id;;
            $field['isDisplayed'] = (bool) $section->show;
            $field['sort'] = (int) $section->order;
            $field['analytics']['viewId'] = null;
            $field['meta']['title'] = empty($section->title) ? null : $section->title;
            $field['meta']['description'] = empty($section->description) ? null : $section->description;
            $field['meta']['keywords'] = empty($section->keyword) ? null : $section->keyword;
            $field['meta']['cover'] = null;
            $field['creationDate'] = $section->created_date;
            // if($section->status == 0) {
            //     $field['removedAt'] = $section->modified_date;
            // }
            $field['removedAt'] = null;

            $channel = Channels::withTrashed()->updateOrCreate(['mediaId' => $medium->id, 'oId' => $section->id], $field);

            Cache::forget('channels:' . $channel->id);
            Cache::forget('channels:all');
            // Cache::forever('channels:' . $channel->id, $channel->load('media'));
            $this->line(is_null($channel) ? 'empty' : sprintf('Extracted %s', $channel->name));
        }

        Cache::increment('inChannel', $interval);
    }
}
