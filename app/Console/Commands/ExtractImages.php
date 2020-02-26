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

class ExtractImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data images';

    private $client;
    private $uri = 'https://api.gridtechno.com/site/old';
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
        $this->client = new Client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Cache::forget('inImage');
        // return;
        $skip = Cache::get('inImage', 0);
        $interval = 100;
        $total = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'photo', 'type' => 'count']
        ])->getBody();

        $media = Media::withTrashed()->with('group')->latest('lastUpdate')->get();

        if ($skip >= (int) $total->getContents()) {
            Cache::forget('inImage');
            return;
        }

        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'photo', 'skip' => $skip, 'take' => $interval, 'order' => 'created_date']
        ])->getBody();

        $field = ['type' => 'images'];
        foreach (json_decode($client->getContents()) as $image) {

            $medium = $media->where('oId', $image->site_id == 0 ? rand(1, $media->count()) : $image->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $created = Carbon::parse($image->created_date);
            $imgHeaders = get_headers($image->src);
            if (strpos($imgHeaders[0], '404') !== false || strpos($imgHeaders[0], '403') !== false) {
                $img = Image::canvas(800, 600)->text($imgHeaders[0], 120, 100);
            } else {
                $img = @getimagesize($image->src)
                  ? Image::make($image->src)
                  : Image::canvas(800, 600)->text($image->caption, 120, 100);
            }

            $path = sprintf('%s/%s/images/%04d/%02d/%02d/', Str::slug($medium->group->name), Str::slug($medium->name), $created->year, $created->month, $created->day);
            $filename = empty($image->caption) ? $medium->name : $image->caption;
            $field['mediaId'] = $medium->id;
            $field['meta']['caption'] = $image->caption;
            $field['meta']['source'] = empty($image->source) ? null : $image->source;
            $field['meta']['credit'] = empty($image->author) ? null : $image->author;
            $field['meta']['filename'] = sprintf('%s-%s.jpeg', Str::slug($filename), $image->id);
            $field['meta']['path'] = $path . $field['meta']['filename'];
            $field['meta']['dimension']['height'] = $img->height();
            $field['meta']['dimension']['width'] = $img->width();
            $field['meta']['size'] = $img->filesize();
            $field['creationDate'] = $created;
            // if (!$image->status) {
            //     $field['removedAt'] = Carbon::now();
            // }

            $imageModel = Galleries::withTrashed()->updateOrCreate(['oId' => $image->id], $field);
            Storage::put($field['meta']['path'], $img->encode('jpeg'), 'public');
            Cache::forget('galleries:' . $imageModel->id);
            Cache::forget('galleries:images:all');
            Cache::forever('galleries:' . $imageModel->id, $imageModel->load('media'));
            $this->line(is_null($imageModel) ? 'empty' : sprintf('Extracted %s', $imageModel->meta['caption']));
        }

        Cache::increment('inImage', $interval);
    }
}
