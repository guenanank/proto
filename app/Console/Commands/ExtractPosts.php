<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Cache;

use App\User;
use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
use App\Models\MongoDB\Galleries;
use App\Models\MongoDB\Posts;

class ExtractPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data articles';

    private $dom;
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
        $this->dom = new \DOMDocument;
        $this->client = new Client(['headers' => $this->headers]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return Cache::forget('inPost');
        $skip = Cache::get('inPost', 0);
        $interval = 20;

        $media = Cache::get('media:withTrashed', function () {
            return Media::withTrashed()->with('group')->get();
        });

        $channels = Cache::get('channels:withTrashed:plucked', function () {
            return Channels::withTrashed()->pluck('_id', 'oId')->all();
        });

        $users = Cache::get('users:all', function () {
            return User::all();
        });

        $total = $this->client->get($this->uri . 'count', [
          'query' => [
            'table' => 'article',
            'where' => true,
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00'
          ]
        ])->getBody();

        if ($skip >= (int) $total->getContents()) {
            return Cache::forget('inPost');
        }

        $client = $this->client->get($this->uri . 'articles', [
          'query' => [
            'field' => 'created_date',
            'operand' => '>=',
            'param' => '0000-00-00 00:00:00',
            'skip' => $skip,
            'take' => $interval,
            'order' => 'created_date'
          ]
        ])->getBody();

        foreach (json_decode($client->getContents()) as $article) {
            $medium = $media->where('oId', $article->site->id)->first();
            if (empty($medium->group)) {
                continue;
            }

            if (empty($article->section->id)) {
                continue;
            }

            $channel = $channels[$article->section->id];

            if (is_null($medium) || is_null($article->authors) || is_null($article->editor)) {
                continue;
            }

            libxml_use_internal_errors(true);
            $this->dom->loadHTML($article->content);
            libxml_use_internal_errors(false);
            $this->dom->preserveWhiteSpace = false;

            $blockqoutes = $this->dom->getElementsByTagName('blockquote');
            if ($blockqoutes->length > 0) {
                foreach ($blockqoutes as $blockqoute) {
                    $class = $blockqoute->getAttribute('class');
                    if ($class == 'instagram-media') {
                        $instagramPermalink = $blockqoute->getAttribute('data-instgrm-permalink');
                        $instagram = $this->dom->createElement('p', Str::before($instagramPermalink, '?'));
                        $blockqoute->parentNode->replaceChild($instagram, $blockqoute);
                    } elseif ($class == 'twitter-tweet') {
                        $link = $blockqoute->getElementsByTagName('a')->item(0);
                        $twitter = $this->dom->createElement('p', $link->getAttribute('src'));
                        $blockqoute->parentNode->replaceChild($twitter, $blockqoute);
                    }
                }
            }

            //
            preg_match("/<body[^>]*>(.*?)<\/body>/is", $this->dom->saveHTML(), $match);

            // $body = $article->content;
            $body = collect(explode('</p>', $match[1]))->transform(function ($paragraph) {

                // remove enclosed paragraph tag
                $paragraph = trim(preg_replace('#<p(.*?)>#is', '', $paragraph));

                //remove hr
                $paragraph = trim(str_replace(['<hr>', '<hr />'], '', $paragraph));

                // remove \n (newlines)
                $paragraph = trim(str_replace("\n", '', $paragraph));

                return $paragraph;
            })->reject(function ($paragraph) {
                return $paragraph == '&nbsp;';
            })->filter()->all();

            $data = [
              'type' => 'articles',
              'mediaId' => $medium->id,
              'channelId' => $channel,
              'oId' => $article->id,
              'headlines' => [
                'title' => $article->title,
                'subtitle' => null,
                'description' => $article->description,
                'tag' => collect($article->tags)->pluck('name')->toArray()
              ],
              'editorials' => [
                'welcomePage' => $article->allow_wp,
                'headline' => !is_null($article->headline),
                'choice' => !is_null($article->choice),
                'advertorial' => (bool) $article->section->name === 'Advertorial',
                'source' => collect($article->sources)->map(function ($source) {
                    return [
                      'url' => $source->website,
                      'name' => $source->name
                    ];
                })->toArray()
              ],
              'published' => $article->published_date,
              'body' => $body,
              // 'body' => $this->getContentAttribute($article, $medium),
              'reporter' => collect($article->authors)->pluck('id')->toArray(),
              'editor' => $article->editor->id,
              'relates' => collect($article->relates)->pluck('id')->toArray(),
              'commentable' => $article->allow_comment,
              'topics' => collect($article->topics)->pluck('id')->toArray(),
              'creationDate' => $article->created_date,
            ];

            if ($article->basket_id == 4) {
                $data['removedAt'] = Carbon::now()->toDateTimeString();
            }

            // dump($data);
            $articleModel = Posts::create($data);
            // UpdateArticle::dispatch($create)->delay(now()->addMinutes(10));
            $this->info(is_null($articleModel) ? 'empty' : sprintf('Extracted %s', $articleModel->headlines['title']));
        }

        Cache::increment('inPost', $interval);
    }

    /*
    private function getContentAttribute($article, $medium)
    {
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($article->content);
        libxml_use_internal_errors(false);
        // $dom->preserveWhiteSpace = false;

        $blockqoutes = $dom->getElementsByTagName('blockquote');
        if ($blockqoutes->length > 0) {
            foreach ($blockqoutes as $blockqoute) {
                $class = $blockqoute->getAttribute('class');
                if ($class == 'instagram-media') {
                    $instagramPermalink = $blockqoute->getAttribute('data-instgrm-permalink');
                    $instagram = $dom->createElement('p', Str::before($instagramPermalink, '?'));
                    $blockqoute->parentNode->replaceChild($instagram, $blockqoute);
                } elseif ($class == 'twitter-tweet') {
                    $link = $blockqoute->getElementsByTagName('a')->item(0);
                    $twitter = $dom->createElement('p', $link->getAttribute('src'));
                    $blockqoute->parentNode->replaceChild($twitter, $blockqoute);
                }
            }
        }

        preg_match("/<body[^>]*>(.*?)<\/body>/is", $dom->saveHTML(), $body);
        $value = $body[1];

        // explode to collection
        $value = collect(explode('</p>', $value))->transform(function ($paragraph) use ($article, $medium) {

            // remove enclosed paragraph tag
            $paragraph = trim(preg_replace('#<p(.*?)>#is', '', $paragraph));

            //remove hr
            $paragraph = trim(str_replace(['<hr>', '<hr />'], '', $paragraph));

            // remove \n (newlines)
            $paragraph = trim(str_replace("\n", '', $paragraph));

            // replace image
            if (preg_match('/<img[^>]* src=\"([^\"]*)\"[^>]*>/', $paragraph, $match)) {
                $domImg = new \DOMDocument();
                $domImg->loadHTML($match[0]);
                $img = $domImg->getElementsByTagName('img')->item(0);
                $src = preg_replace('/(crop.*\/photo)/', 'photo', $img->getAttribute('src'));
                $image = @getimagesize($src);

                $data = [
                  // 'mediaId' => $medium->id,
                  // 'type' => 'images',
                  'meta' => [
                    'caption' => $img->getAttribute('data-caption'),
                    'source' => $img->getAttribute('data-source'),
                    'credit' => $img->getAttribute('data-author'),
                    'filename' => null,
                    'path' => null,
                    'oUrl' => $src,
                    'dimension' => [
                      'height' => $image[0],
                      'width' => $image[1]
                    ],
                    'size' => null,
                    'creationDate' => $article->created_date
                  ]
                ];

                $paragraph = Galleries::firstOrNew(['mediaId' => $medium->id, 'type' => 'images', 'meta.oUrl' => $src], $data)->toArray();
            }

            // replace iframe
            elseif (preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $paragraph, $match)) {
                $dom = new \DOMDocument();
                $dom->loadHTML($match[0]);
                $iframe = $dom->getElementsByTagName('iframe')->item(0);
                $src = $iframe->getAttribute('src');

                $data = [
                  // 'mediaId' => $medium->id,
                  // 'type' => 'videos',
                  'meta' => [
                    'title' => $iframe->getAttribute('data-title'),
                    'description' => $iframe->getAttribute('data-description'),
                    'youtubeId' => Str::after('https://www.youtube.com/embed/', $src),
                    'cover' => [
                      'name' => null,
                      'path' => null,
                    ],
                    'embed' => $src,
                    'published' => $article->created_date,
                    'statistics' => [],
                    'creationDate' => $article->created_date
                  ]
                ];

                $paragraph = Galleries::firstOrNew(['mediaId' => $medium->id, 'type' => 'videos', 'meta.embed' => $src], $data)->toArray();
            }

            return $paragraph;
        })->reject(function ($paragraph) {
            return $paragraph == '&nbsp;';
        })->filter()->all();

        // dd($value);
        return $value;
    }
    */
}
