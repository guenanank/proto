<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Topics;

class ExtractTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:topic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data topics';

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
        // Cache::forget('inTopic');
        // return;
        $skip = Cache::get('inTopic', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => ['table' => 'topic']
        ])->getBody();

        $media = Cache::rememberForever('media:all', function() {
            return Media::withTrashed()->with('group')->get();
        });

        if ($skip >= (int) $total->getContents()) {
            Cache::forget('inTopic');
            return;
        }

        $client = $this->client->get($this->uri . 'topics', [
          'query' => ['skip' => $skip, 'take' => $interval, 'order' => 'created_date']
        ])->getBody();

        foreach(json_decode($client->getContents()) as $topic) {

            $medium = $media->where('oId', $topic->site_id == 0 ? rand(1, $media->count()) : $topic->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $field['mediaId'] = $medium->id;
            $field['title'] = $topic->name;
            $field['slug'] = Str::slug($topic->name);
            $field['published'] = Carbon::parse($topic->created_date);
            $field['meta']['description'] = empty($topic->description) ? null : $topic->description;
            $field['meta']['isBrandstory'] = (bool) is_null($topic->type_ads) ? false : true;
            $field['creationDate'] = Carbon::parse($topic->created_date);
            if(!$topic->status) {
                $field['removedAt'] = Carbon::parse($topic->modified_date);
            }

            if(!empty($topic->photo_url)  && filter_var($topic->photo_url, FILTER_VALIDATE_URL)) {
                $path = sprintf('%s/%s/topics/', Str::slug($medium->group->name), Str::slug($medium->name));
                $filename = sprintf('%s-%s.jpeg', Str::slug($topic->name), $topic->id);
                $field['meta']['cover'] = $filename;

                $imgHeaders = get_headers($topic->photo_url);
                $img = strpos($imgHeaders[0], '404') !== false || strpos($imgHeaders[0], '403') !== false
                  ? Image::canvas(800, 600)->text($imgHeaders[0], 120, 100)
                  : Image::make($topic->photo_url);

                Storage::put($path . $filename, $img->encode('jpeg'), 'public');
            }

            $topicModel = Topics::withTrashed()->updateOrCreate(['oId' => $topic->id], $field);

            Cache::forget('topics:' . $topicModel->id);
            Cache::forget('topics:all');
            // Cache::forever('topics:' . $topicModel->id, $topicModel->load('media'));
            $this->line(is_null($topicModel) ? 'empty' : sprintf('Extracted %s', $topicModel->title));

        }
        Cache::increment('inTopic', $interval);
    }
}
