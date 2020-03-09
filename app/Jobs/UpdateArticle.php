<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Groups;
use App\Models\MongoDB\Posts;
use App\Models\MongoDB\Galleries;

class UpdateArticle implements ShouldQueue
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
        
        $skip = Cache::get('upArticle', 0);
        $interval = 10;

        if ($skip >= Posts::articles()->count()) {
            return Cache::forget('upArticle');
        }

        $articles = Posts::articles()->oldest('oId')->skip($skip)->take($interval)->get();


        foreach ($articles as $article) {
            $article->body->map(function ($paragraph) use($article) {
                if (is_array($paragraph)) {


                    $whereField = $paragraph['type'] == 'images' ? 'oUrl' : 'embed';
                    // $gallery = Galleries::firstOrNew(['type' => $paragraph['type'], 'meta->' . $whereField => $paragraph['meta'][$whereField]], $paragraph);

                    $paragraph = $gallery->toArray();
                }
            });

            dd($article->body);
            // update relates
            // update read too
            // update ?
            // $this->article->relates = null;
            // $this->article->save();
            //
            // Cache::forget('posts:articles:' . $this->article->id);
            Cache::forget('posts:articles:all');
            // Cache::forever('posts:articles:' . $this->article->id);
        }

        Cache::increment('upArticle', $interval);

        dd('stop');
    }
}
