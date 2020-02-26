<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\Elasticsearch\Articles;
use App\User;
use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
use App\Models\MongoDB\Posts;

use App\Jobs\UpdateArticle;

class ExtractArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data articles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Cache::forget('inArticle');
        // return;

        $skip = Cache::get('inArticle', 0);
        $interval = 100;

        $media = Cache::rememberForever('mediaWithTrashed', function () {
            return Media::withTrashed()->pluck('_id', 'oId')->all();
        });

        $channels = Cache::rememberForever('channelsAll', function () {
            return Channels::pluck('_id', 'oId')->all();
        });

        $users = Cache::rememberForever('usersAll', function () {
            return User::all();
        });

        if ($skip >= Articles::count()) {
            Cache::forget('inArticle');
            return;
        }

        $articles = Articles::orderBy('id', 'asc')->skip($skip)->take($interval)->get();
        foreach ($articles as $article) {

            if (is_null($article->section)) {
                continue;
            }

            $medium = $media[$article->site['id']];
            $channel = $channels[$article->section['id']];
            $reporters = $users->whereInStrict('oId', array_column($article->author, 'id'));

            if (is_null($medium) || is_null($channel) || is_null($reporters)) {
                continue;
            }

            $data = [
              'mediaId' => $medium,
              'channelId' => $channel,
              'type' => 'articles',
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
                'advertorial' => (bool) $article->section['name'] == 'Advertorial',
                'source' => $article->source
              ],
              'published' => $article->published_date,
              'body' => $article->content,
              'assets' => [
                'photo' => $article->photo,
                'iframe' => $article->iframe
              ],
              'reporter' => $reporters,
              'editor' => $article->editor,
              'commentable' => $article->allow_comment,
              'creationDate' => $article->published_date
            ];

            // dd($data);
            $articleModel = Posts::updateOrCreate(['oId' => $article->id], $data);
            // UpdateArticle::dispatch($create)->delay(now()->addMinutes(10));
            $this->line(is_null($articleModel) ? 'empty' : sprintf('Extracted %s', $articleModel->headlines['title']));
        }

        Cache::increment('inArticle', $interval);
    }
}
