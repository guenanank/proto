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
    private $uri = 'https://api.grid.id/site/old';
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
    public function handle(Galleries $gallery)
    {
        $lastId = $gallery->images()->withTrashed()->count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'photo', 'skip' => $lastId, 'take' => 100, 'order' => 'created_date']
        ])->getBody();

        $images = json_decode($client->getContents());
        $media = Media::with('group')->withTrashed()->get();

        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        $field = ['type' => 'images'];
        foreach ($images as $image) {
            $medium = $media->where('oId', $image->site_id == 0 ? rand(1, $media->count()) : $image->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $created = Carbon::parse($image->created_date);

            $imgHeaders = get_headers($image->src);
            if (strpos($imgHeaders[11], '404') !== false) {
                $img = Image::canvas(800, 600)->text($imgHeaders[11], 120, 100);
            } else {
                $img = @getimagesize($image->src)
                  ? Image::make($image->src)
                  : Image::canvas(800, 600)->text($image->caption, 120, 100);
            }

            // $img = file_exists($image->src) && getimagesize($image->src)
            //   ? Image::canvas(800, 600)->text($image->caption, 120, 100)
            //   : Image::make($image->src);

            // $imgHeaders = get_headers($image->src);
            // if (strpos($imgHeaders[11], '404') !== false) {
            //     $img = Image::canvas(800, 600)->text($imgHeaders[11], 120, 100);
            // } else {
            //     $getContent = file_get_contents($image->src);
            //     if (empty($getContent)) {
            //         $img = Image::canvas(800, 600)->text($image->caption, 120, 100);
            //     } else {
            //         if (ctype_print($getContent)) {
            //             $img = Image::canvas(800, 600)->text('Unable to init from given binary data.', 120, 100);
            //         } else {
            //             $img = preg_match('~[^\x20-\x7E\t\r\n]~', $getContent) > 0
            //               ? Image::make($image->src)
            //               : Image::canvas(800, 600)->text('Unable to init from given binary data.', 120, 100);
            //         }
            //     }
            // }

            $path = sprintf('%s/%s/images/%04d/%02d/%02d/', Str::slug($medium->group->name), Str::slug($medium->name), $created->year, $created->month, $created->day);
            $field['mediaId'] = $medium->id;
            $field['meta']['caption'] = empty($image->caption) ? sprintf('%s-%s', Str::slug($medium->name), Str::uuid()) : $image->caption;
            $field['meta']['source'] = empty($image->source) ? null : $image->source;
            $field['meta']['credit'] = empty($image->author) ? null : $image->author;
            $field['meta']['filename'] = Str::slug($field['meta']['caption']) . '.' . pathinfo($image->src, PATHINFO_EXTENSION);
            $field['meta']['path'] = $path . $field['meta']['filename'];
            $field['meta']['mime'] = $img->mime();
            $field['meta']['dimension']['height'] = $img->height();
            $field['meta']['dimension']['width'] = $img->width();
            $field['meta']['size'] = $img->filesize();
            $field['oId'] = $image->id;
            if (!$image->status) {
                $field['removedAt'] = Carbon::now();
            }
            $field['creationDate'] = $created;

            $exec = Galleries::updateOrCreate($field, ['oId' => $image->id]);
            Storage::put($field['meta']['path'], $img->encode(), 'public');
            Cache::forever('galleries:images:' . $exec->id, $exec->load('media'));

            $bar->advance();
        }
        $bar->finish();
    }
}
