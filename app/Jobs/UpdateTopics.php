<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Topics;

class UpdateTopics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;
    protected $medium;
    private $client;
    private $uri = 'https://api.grid.id/site/old';
    private $headers = [
      'Content-Type' => 'application/json',
      'Api-Token' => '$2y$10$c1V7USh1HZSr9irAuwVcpOIRoYWhE4PCPI9jh31y4KXnoq4B3DA9C'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topics $topic)
    {
        $this->topic = $topic;
        $this->medium = Media::with('group')->findOrFail($topic->mediaId);
        if(empty($this->medium)) {
          return;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client;
        $body = $client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'topic', 'id' => $this->topic->oId]
        ])->getBody();

        $topic = json_decode($body->getContents());
        $topic = reset($topic);

        $data = [
          'title' => $topic->name,
          'slug' => Str::slug($topic->name),
          'meta' => [
            'description' => $topic->description,
            'isBrandstory' => (bool) is_null($topic->type_ads) ? false : true
          ]
        ];

        if(!empty($topic->photo_url)) {
            $path = sprintf('%s/%s/topics/', Str::slug($this->medium->group->name), Str::slug($this->medium->name));
            $filename = sprintf('%s-%s.webp', Str::slug($topic->name), $this->topic->id);
            $data['meta']['cover'] = $filename;

            $imgHeaders = get_headers($topic->photo_url);
            $img = strpos($imgHeaders[11], '404') !== false
              ? Image::canvas(800, 600)->text($imgHeaders[11], 120, 100)
              : Image::make($topic->photo_url);

            Storage::put($path . $filename, $img->encode('webp'), 'public');
        }

        if(!$topic->status) {
            $data['removedAt'] = now();
        }

        $this->topic->update($data);
        Cache::forget('topics:' . $this->topic->id);
        Cache::forget('topics:all');
        Cache::forever('topics:' . $this->topic->id, $this->topic);
    }
}
