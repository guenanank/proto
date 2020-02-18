<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
use App\Models\MongoDB\Topics;
use App\Models\MongoDB\Galleries;

/**
 *
 */
class Extract extends Controller
{
    protected $client;
    protected $uri = 'https://api.grid.id/site/old';
    protected $headers = [
      'Content-Type' => 'application/json',
      'Api-Token' => '$2y$10$c1V7USh1HZSr9irAuwVcpOIRoYWhE4PCPI9jh31y4KXnoq4B3DA9C'
    ];

    public function __construct()
    {
        $this->client = new Client;
    }

    public function site()
    {
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'site']
        ])->getBody();

        $network = [
          'Gridoto' => 'GRIDOTO',
          'Gridnetwork' => 'GRID',
          'Motorplus' => 'GRID',
          'Bolasport' => 'BOLASPORT',
          'sonora' => 'GRID',
          '' => null
        ];

        foreach (json_decode($client->getContents()) as $site) {
            $group = Cache::get('groups:all')->where('code', $network[$site->network])->first();
            Media::updateOrCreate([
              'groupId' => $group ? $group->id : null,
              'name' => $site->name,
              'domain' => $site->url,
              'analytics' => [
                'gaId' => $site->ga_view_id,
                'youtubeChannel' => $site->youtube_channel,
              ],
              'meta' => [
                  'title' => $site->title,
                  'keywords' => $site->keyword,
                  'description' => $site->description,
                  'color' => null
              ],
              'assets' => [
                'logo' => null,
                'logoAlt' => null,
                'icon' => null,
                'css' => null,
                'js' => null
              ],
              'masthead' => [
                'about' => null,
                'editorial' => null,
                'management' => null,
                'contact' => null
              ],
              'oId' => sprintf('%02d', $site->id),
            ], ['oId' => $site->id]);

            echo sprintf('%s %s </br>', $site->id, $site->name);
        }
    }

    public function section()
    {
        $lastId = Channels::withTrashed()->count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'section', 'skip' => $lastId, 'take' => 10, 'order' => 'modified_date']
        ])->getBody();

        $channels = json_decode($client->getContents());
        $media = Cache::get('media:withTrashed', function () {
            return Media::with('group')->withTrashed()->get();
        });

        foreach ($channels as $channel) {
            $medium = $media->where('oId', $channel->site_id == 0 ? rand(1, $media->count()) : $channel->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $field['mediaId'] = $medium->id;
            $field['name'] = $channel->name;
            $field['slug'] = Str::slug($channel->name);
            $field['sub'] = null;
            $field['isDisplayed'] = (bool) $channel->show;
            $field['sort'] = (int) $channel->order;
            $field['analytics']['viewId'] = null;
            $field['meta']['title'] = empty($channel->title) ? null : $channel->title;
            $field['meta']['description'] = empty($channel->description) ? null : $channel->description;
            $field['meta']['keywords'] = empty($channel->keyword) ? null : $channel->keyword;
            $field['meta']['cover'] = null;
            $field['oId'] = $channel->id;
            $field['creationDate'] = Carbon::parse($channel->created_date);
            if (!$channel->status) {
                $field['removedAt'] = Carbon::now();
            }

            $exec = Channels::create($field);
            dump($exec);
            // UpdateChannels::dispatch($exec);
            Cache::forget('channels:all');
        }

        // $client = $this->client->get($this->uri, [
        //   'headers' => $this->headers,
        //   'query' => ['table' => 'section']
        // ])->getBody();
        //
        // $media = Media::withTrashed()->pluck('_id', 'oId');
        // foreach (json_decode($client->getContents()) as $section) {
        //     Channels::updateOrCreate([
        //         'mediaId' => $media->get($section->site_id),
        //         'name' => $section->name,
        //         'slug' => Str::slug($section->name),
        //         'sub' => is_null($sub) ? 0 : $sub->oId,
        //         'displayed' => $section->show,
        //         'sort' => $section->order,
        //         'analytics' => ['gaId' => null],
        //         'meta' => [
        //           'title' => $section->title,
        //           'description' => $section->description,
        //           'keywords' => $section->keyword,
        //           'cover' => null
        //         ],
        //         'removedAt' => $section->status ? null : Carbon::now(),
        //         'oId' => $section->id
        //     ], ['oId' => $section->id]);
        //
        //     if ($section->parent && $section->parent > 0) {
        //         $parent = Channels::withTrashed()->where('oId', $section->parent)->first();
        //         Channels::where('oId', $section->id)->update([
        //           'sub' => $parent->_id
        //         ]);
        //     }
        //
        //     echo sprintf('%s %s </br>', $section->id, $section->name);
        // }
    }

    public function topic()
    {
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'topic', 'id' => 768]
        ])->getBody();

        $topic = json_decode($client->getContents());
        // $topic = reset($topic);
        dd($topic[0]->name);
        // $topic = json_decode($client->getContents())[0];
        // $update = Topics::where('oId', $topic->id)->update([
        //   'title' => $topic->name,
        //   'meta' => [
        //     'description' => $topic->description,
        //     'cover' => $topic->photo_url,
        //   ],
        // ]);
        //
        // if ($update) {
        //     $counter + 1;
        // }
        // $client = $this->client->get($this->uri, [
        //   'headers' => $this->headers,
        //   'query' => ['table' => 'topic']
        // ])->getBody();
        //
        // $media = Media::withTrashed()->pluck('_id', 'oId');
        //
        // foreach (json_decode($client->getContents()) as $topic) {
        //     if (is_null($topic->site_id) || $topic->site_id < 1) {
        //         continue;
        //     }
        //
        //     Topics::updateOrCreate([
        //       'mediaId' => $media->get($topic->site_id),
        //       'title' => $topic->name,
        //       'slug' => Str::slug($topic->name),
        //       'published' => Carbon::parse($topic->created_date),
        //       'meta' => [
        //         'description' => $topic->description,
        //         'cover' => $topic->photo_url,
        //       ],
        //       'oId' => $topic->id,
        //       'removedAt' => $topic->status ? null : Carbon::parse($topic->modified_date),
        //     ], ['oId' => $topic->id]);
        //
        //     echo sprintf('%s %s </br>', $topic->id, $topic->name);
        // }
    }

    public function image()
    {
        $lastId = Galleries::images()->withTrashed()->count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'photo', 'skip' => $lastId, 'take' => 50, 'order' => 'created_date']
        ])->getBody();

        $field = ['type' => 'images'];
        $media = Cache::get('media:all', function () {
            return Media::withTrashed()->get();
        });

        foreach (json_decode($client->getContents()) as $image) {
            $medium = $media->where('oId', $image->site_id == 0 ? 1 : $image->site_id)->first();
            $created = Carbon::parse($image->created_date);

            $imgHeaders = get_headers($image->src);
            if (strpos($imgHeaders[11], '404') !== false) {
                $img = Image::canvas(800, 600)->text($imgHeaders[11], 120, 100);
            } else {
                $getContent = file_get_contents($image->src);
                if (empty($getContent)) {
                    $img = Image::canvas(800, 600)->text($image->caption, 120, 100);
                } else {
                    $img = ctype_print($getContent) || (preg_match('~[^\x20-\x7E\t\r\n]~', $getContent) > 0)
                      ? Image::canvas(800, 600)->text('Unable to init from given binary data.', 120, 100)
                      // ? Image::make(base64_decode($image->src))
                      : Image::make($image->src);
                }
            }

            // dd($isValidImg);
            // $i = $this->client->get($image->src);
            // dd($i->getHeader());
            // $imgHeaders = get_headers($image->src);
            // dd($imgHeaders);
            // $img = empty(file_get_contents($image->src)) || strpos($imgHeaders[11], '404') !== false
            //   ? Image::canvas(800, 600)
            //   : Image::make($image->src);

            // dd($medium->group->code);
            // $path = sprintf('galleries/%s/%s/images/%s/%s/%s/', strtolower($medium->group->code), Str::slug($medium->name), $created->year, $created->month, $created->day);
            // $field['mediaId'] = $medium->id;
            // $field['meta']['caption'] = empty($image->caption) ? Str::uuid() : $image->caption;
            // $field['meta']['source'] = $image->source;
            // $field['meta']['credit'] = $image->author;
            // $field['meta']['filename'] = Str::slug($field['meta']['caption']) . '.' . pathinfo($image->src, PATHINFO_EXTENSION);
            // $field['meta']['path'] = $path . $field['meta']['filename'];
            // $field['meta']['mime'] = $img->mime();
            // $field['meta']['dimension']['height'] = $img->height();
            // $field['meta']['dimension']['width'] = $img->width();
            // $field['meta']['size'] = $img->filesize();
            // $field['oId'] = $image->id;
            // if(!$image->status) {
            //   $field['removedAt'] = Carbon::now();
            // }
            //
            // Galleries::updateOrCreate($field, ['oId' => $image->id]);
            // Storage::put($field['meta']['path'], $img->encode(), 'public');
            // dump($field);
            // Galleries::create($field);
        }
    }
}
