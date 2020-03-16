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
        // return Cache::forget('inImage');
        $skip = Cache::get('inImage', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => [
            'table' => 'photo',
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
            return Cache::forget('inImage');
        }

        $client = $this->client->get($this->uri . 'images', [
          'query' => [
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00',
            'skip' => $skip,
            'take' => $interval,
            'order' => 'created_date'
          ]
        ])->getBody();

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

            $aliasSubdomain = Str::after(Str::before($medium->domain, '.'), 'https://');
            $path = sprintf('%s/%s/images/%04d/%02d/%02d/', strtolower($medium->group->code), $aliasSubdomain, $created->year, $created->month, $created->day);
            $filename = empty($image->caption) ? $medium->name : $image->caption;
            
            $field['meta']['caption'] = $image->caption;
            $field['meta']['source'] = empty($image->source) ? null : $image->source;
            $field['meta']['credit'] = empty($image->author) ? null : $image->author;
            $field['meta']['filename'] = sprintf('%s-%s.jpeg', Str::slug($filename), $image->id);
            $field['meta']['path'] = $path . $field['meta']['filename'];
            $field['meta']['oUrl'] = preg_replace('/(crop.*\/photo)/', 'photo', $image->src);
            $field['meta']['dimension']['height'] = $img->height();
            $field['meta']['dimension']['width'] = $img->width();
            $field['meta']['size'] = $img->filesize();
            $field['creationDate'] = $created;
            if (!$image->status) {
                $field['removedAt'] = Carbon::now();
            }

            $imageModel = Galleries::withTrashed()->updateOrCreate(['type' => 'images', 'mediaId' => $medium->id, 'oId' => $image->id], $field);
            Storage::put($field['meta']['path'], $img->encode('jpeg'), 'public');
            Cache::forget('galleries:' . $imageModel->id);
            Cache::forget('galleries:images:all');
            // Cache::forever('galleries:' . $imageModel->id, $imageModel->load('media'));
            $this->line(is_null($imageModel) ? 'empty' : sprintf('Extracted %s', $imageModel->meta['caption']));
        }

        Cache::increment('inImage', $interval);
    }
}
