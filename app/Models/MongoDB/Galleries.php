<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Galleries extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['mediaId', 'type', 'meta', 'oId', 'removedAt'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'collection'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['media'];

    /**
     * Get the site that owns the gallery.
     */
    public function media()
    {
        return $this->belongsTo('App\Models\MongoDB\Media', 'mediaId', '_id');
    }

    /**
     * Scope a query to only include images.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'images');
    }

    public static function type($type = null)
    {
        $collection = collect(['Images', 'Videos', 'Musics', 'Podcasts']);
        $lists = $collection->combine($collection->map(function ($item) {
            return camel_case($item);
        }))->flip();

        return is_null($type) ? $lists : $lists->get($type);
    }
}
