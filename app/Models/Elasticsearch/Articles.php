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
        'iframe' => 'collection',
        'editor' => 'collection',
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
     * Get the article photos.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoAttribute($value)
    {
        return collect($value)->map(function ($photo) {
            $src = explode('/', $photo['src']);
            $id = Str::before(end($src), '.');
            $image = Galleries::images()->where('oId', $id)->first();
            return is_null($image) ? [] : $image;
        });
    }

    /**
     * Get the article iframe.
     *
     * @param  string  $value
     * @return string
     */
    public function getIframeAttribute($value)
    {
        return collect($value)->map(function ($iframe) {
            $src = explode('/', $iframe['src']);
            $youtubeId = end($src);
            $video = Galleries::videos()->where('meta.youtubeId', $youtubeId)->first();
            return is_null($video) ? [] : $video;
        });
    }

    /**
     * Get the article content.
     *
     * @param  string  $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        // regex blockquote
        $value = preg_replace_callback('/<blockquote.*?=".*?".*?>.*?<\/blockquote>/', function ($match) {
            if (Str::contains($match[0], 'twitter-tweet')) {
                $twitterUrl = explode('?', Str::after($match[0], 'https://twitter.com/'));
                return '<p> <!-- https://twitter.com/' . $twitterUrl[0] . ' --> </p>';
            } elseif (Str::contains($match[0], 'instagram-media')) {
                $instagramId = explode('/', Str::after($match[0], "https://www.instagram.com/p/"));
                return '<p> <!-- https://instagram.com/p/' . $instagramId[0] . ' --> </p>';
            } else {
                return null;
            }
        }, $value);

        // explode to collection
        $value = collect(explode('</p>', $value))->transform(function ($paragraph) {
            $paragraph = trim(preg_replace('#<p(.*?)>#is', null, $paragraph));

            if (preg_match('/<!--img(.*?)-->/', $paragraph, $keys)) {
                $i = (int) Str::before(Str::after($paragraph, '<!--img'), '-->');
                $paragraph = null;
                if(count($this->attributes['photo']) > 0) {
                    $photos = $this->attributes['photo'][$i > 0 ? $i - 1 : $i];
                    dd($photos);
                    $src = explode('/', $photos['src']);
                    $id = Str::before(end($src), '.');
                    $p = Galleries::images()->where('oId', (int) $id)->first();
                    dd($p);
                }
            } elseif (preg_match('/<!--iframe(.*?)-->/', $paragraph, $keys)) {
                $i = (int) Str::before(Str::after($paragraph, '<!--iframe'), '-->');
                $paragraph = $this->attributes['iframe'][$i - 1];
            }

            return $paragraph;
        })->reject(function($paragraph) {
            return $paragraph == '&nbsp;';
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
            return [
              'url' => $source['website'],
              'name' => $source['name']
            ];
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
