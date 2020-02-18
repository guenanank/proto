<?php

namespace App\Models;

use App\Models;
use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galleries extends Models
{
    use SoftDeletes;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new SiteScope);
    }

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['site_id', 'type', 'meta'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'object'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['site:id,name'];

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

    /**
     * Scope a query to only include videos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'videos');
    }

    /**
     * Scope a query to only include podcast.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePodcasts($query)
    {
        return $query->where('type', 'podcasts');
    }

    /**
     * Get the galleries that owns the site.
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Sites', 'site_id', 'id');
    }

    public static function type($type = null)
    {
        $collection = collect(['Images', 'Videos', 'Podcasts']);
        $lists = $collection->combine($collection->map(function ($item) {
            return camel_case($item);
        }))->flip();

        return is_null($type) ? $lists : $lists->get($type);
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'site_id' => 'exists:sites.id|nullable',
          'type' => 'required|string|max:7'
      ])->merge($rules);
    }
}
