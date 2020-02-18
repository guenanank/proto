<?php

namespace App\Models\Elasticsearch;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use Basemkhirat\Elasticsearch\Model;

use App\User;
use App\Models\MongoDB\Galleries;

class Articles extends Model
{
    // protected $type = 'data';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'allow_wp' => 'boolean',
        'allow_comment' => 'boolean',
        'photo' => 'collection',
        'iframe' => 'collection'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_date'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['editor'];

    /**
     * Get the article content.
     *
     * @param  string  $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        $value = collect(explode('</p>', $value))->transform(function ($p, $index) {
            $p = trim(preg_replace('#<p(.*?)>#is', '', $p));

            $posImg = (strpos($p, '!--img', 1)) ? strpos($p, '!--img', 1) : 9999;
            $posIframe = (strpos($p, '!--iframe', 1)) ? strpos($p, '!--iframe', 1) : 9999;

            if ((int) $posImg <= (int) $posIframe) {
                preg_match('/<!--img(.*?)-->/', $p, $matchImg);
                $indexImg = isset($matchImg[1]) && $matchImg[1] ? $matchImg[1] - 1 : 0;
                if (Str::contains($p, '<!--img' . ($indexImg + 1) . '-->')) {
                    $p = $this->attributes['photo'][$indexImg];
                }
            } else {
                preg_match('/<!--iframe(.*?)-->/', $p, $matchFrame);
                $indexFrame = isset($matchFrame[1]) && $matchFrame[1] ? $matchFrame[1] - 1 : 0;
                if (Str::contains($p, '<!--iframe' . ($indexFrame + 1) . '-->')) {
                    $p = $this->attributes['iframe'][$indexFrame];
                }
            }



            // if (preg_match('/<!--img(.*?)-->/', $p, $keys)) {
            //     $cover = $this->attributes['photo'][$index];
            //     $coverId = explode('/', $cover['src']);
            //     $coverId = explode('.', end($coverId));
            //     $coverImg = Galleries::images()->where('oId', $coverId[0])->first();
            //     if (Str::contains($p, '<!--img' . $index . '-->')) {
            //         $p = empty($coverImg) ? $cover : $coverImg;
            //     }
            // } elseif (preg_match('/<!--iframe(.*?)-->/', $p, $keys)) {
            //     if (Str::contains($p, '<!--iframe' . $index . '-->')) {
            //         $p = $this->attributes['iframe'][$index];
            //     }
            // }

            return $p;
        })->filter();

        dd($value);
        return $value;
    }

    /**
     * Get the article tags.
     *
     * @param  string  $value
     * @return string
     */
    public function getTagAttribute($value)
    {
        return collect($value)->map(function ($tag) {
            $site = $this->attributes['site'];
            return [
                'url' => sprintf('%s/tag/%s', $site['url'], Str::slug($tag['name'])),
                'title' => Str::title($tag['name'])
            ];
        });
    }

    /**
     * Get the article published date.
     *
     * @param  string  $value
     * @return string
     */
    public function getPublishedDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * Get the article sources.
     *
     * @param  string  $value
     * @return string
     */
    public function getSourceAttribute($value)
    {
        return collect($value)->map(function ($source) {
            return $source['name'];
        });
    }

    /**
     * Get the article editor.
     *
     * @param  string  $value
     * @return string
     */
    public function getEditorAttribute()
    {
        $publishedBy = $this->attributes['published_by'];
        $editor = User::where('oId', $publishedBy['id'])->first();
        return $editor ? $editor->id : null;
    }
}
