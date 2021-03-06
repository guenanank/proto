<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Galleries;

class ExtractVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data video';

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
        // return Cache::forget('inVideo');
        $skip = Cache::get('inVideo', 0);
        $interval = 200;
        $total = $this->client->get($this->uri . 'count', [
          'query' => [
            'table' => 'video',
            'where' => true,
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00'
          ]
        ])->getBody();

        $media = Cache::get('media:withTrashed', function() {
            return Media::withTrashed()->with('group')->get();
        });

        if ($skip >= (int) $total->getContents()) {
            return Cache::forget('inVideo');
        }

        $client = $this->client->get($this->uri . 'videos', [
          'query' => [
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00',
            'skip' => $skip,
            'take' => $interval,
            'order' => 'created_date'
          ]
        ])->getBody();

        foreach (json_decode($client->getContents()) as $video) {
            $medium = $media->where('oId', $video->site_id == 0 ? rand(1, $media->count()) : $video->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $created = Carbon::parse($video->created_date);
            $cover = str_replace('sddefault', 'hqdefault', $video->photo);
            $imgHeaders = get_headers($cover);
            if (strpos($imgHeaders[0], '404') !== false || strpos($imgHeaders[0], '403') !== false) {
                $img = Image::canvas(800, 600)->text($imgHeaders[0], 120, 100);
            } else {
                $img = @getimagesize($cover)
                  ? Image::make($cover)
                  : Image::canvas(800, 600)->text($video->title, 120, 100);
            }

            $aliasSubdomain = Str::after(Str::before($medium->domain, '.'), 'https://');
            $path = sprintf('%s/%s/videos/%04d/%02d/%02d/', strtolower($medium->group->code), $aliasSubdomain, $created->year, $created->month, $created->day);
            $field['mediaId'] = $medium->id;
            $field['meta']['title'] = $video->title;
            $field['meta']['description'] = $video->description;
            $field['meta']['youtubeId'] = $video->video_id;
            $field['meta']['cover']['name'] = sprintf('%s-%s.jpeg', Str::slug($video->title), $video->video_id);
            $field['meta']['cover']['path'] = $path . $field['meta']['cover']['name'];
            $field['meta']['embed'] = sprintf('https://www.youtube.com/embed/%s', $video->video_id);
            $field['meta']['published'] = Carbon::parse($video->published_date);
            $field['meta']['statistics'] = [];
            $field['meta']['oId'] = $video->id;
            $field['creationDate'] = $created;
            if (!$video->status) {
                $field['removedAt'] = Carbon::parse($video->modified_date);
            }

            $videoModel = Galleries::updateOrCreate(['type' => 'videos', 'oId' => $video->id], $field);
            Storage::put($field['meta']['cover']['path'], $img->encode('jpeg'), 'public');
            Cache::forget('galleries:' . $videoModel->id);
            Cache::forget('galleries:videos:all');
            // Cache::forever('galleries:videos:' . $videoModel->id, $videoModel->load('media'));
            $this->line(is_null($videoModel) ? 'empty' : sprintf('Extracted %s', $videoModel->meta['title']));
        }

        Cache::increment('inVideo', $interval);
    }
}
