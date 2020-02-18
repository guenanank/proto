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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\Elasticsearch\Articles;
use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
use App\User;
use App\Models\MongoDB\Posts;

class ExtractArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Cache::forget('art');
        $skip = Cache::increment('art');
        $articles = Articles::orderBy('id', 'desc')->skip($skip)->take(10)->get();

        foreach ($articles as $article) {

            $medium = Media::where('oId', $article->site['id'])->firstOrFail();
            $channel = Channels::where('oId', $article->section['id'])->firstOrFail();

            if (empty($medium) || empty($channel)) {
                continue;
            }

            $data = [
              'mediaId' => $medium->id,
              'channelId' => $channel->id,
              'type' => 'article',
              'headlines' => [
                'title' => $article->title,
                'subtitle' => null,
                'description' => $article->description,
                'tag' => $article->tag
              ],
              'editorials' => [
                'welcomePage' => $article->allow_wp,
                'headline' => false,
                'choice' => false,
                'advertorial' => (bool) $channel->name == 'Advertorial',
                'source' => $article->source
              ],
              'published' => $article->published_date,
              'body' => $article->content,
              'assets' => [
                'photo' => collect($article->photo)->each(function($photo) {
                    unset($photo['block']);
                    return $photo;
                }),
                'iframe' => collect($article->iframe)->each(function($iframe) {
                    unset($iframe['block']);
                    return $iframe;
                })
              ],
              'reporter' => User::whereIn('oId', array_column($article->author, 'id'))->get(),
              'editor' => $article->editor,
              'commentable' => $article->allow_comment,
              'oId' => $article->id,
              'creationDate' => $article->published_date
            ];

            $exec = Posts::create($data);
        }
    }
}
