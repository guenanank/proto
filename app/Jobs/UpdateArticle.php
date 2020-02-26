<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\Elasticsearch\Articles;
use App\Models\MongoDB\Posts;

class UpdateArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Posts $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $article = Articles::find($this->article->oId);

        // update relates
        // update read too
        // update ?
        $this->article->relates = null;
        $this->article->save();

        Cache::forget('posts:articles:' . $this->article->id);
        Cache::forget('posts:articles:all');
        Cache::forever('posts:articles:' . $this->article->id);
    }
}
