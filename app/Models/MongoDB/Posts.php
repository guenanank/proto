<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use App\Scopes\SiteScope;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Posts extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
      'mediaId',
      'channelId',
      'type',
      'headlines',
      'editorials',
      'published',
      'body',
      'assets',
      'reporter',
      'editor',
      'commentable',
      'analytics',
      'oId',
      'creationDate',
      'removedAt'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'headlines' => 'collection',
        'editorials' => 'collection',
        'assets' => 'collection',
        'reporter' => 'collection',
        'editor' => 'collection',
        'commentable' => 'boolean',
        'analytics' => 'collection',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['media', 'channels'];

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
     * Get the media that owns the articles.
     */
    public function media()
    {
        return $this->belongsTo('App\Models\MongoDB\Media', 'mediaId', '_id');
    }

    public function getTypeAttribute($value)
    {
        return self::type($value);
    }

    public static function type($type = null)
    {
        $collection = collect(['Articles', 'Images', 'Videos', 'Podcasts', 'Recipes', 'Pricelists', 'Charts', 'Pollings']);
        $lists = $collection->combine($collection->map(function ($item) {
            return camel_case($item);
        }))->flip();

        return is_null($type) ? $lists : $lists->get($type);
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'headlines.title' => 'required',
          'headlines.subtitle' => '',
          'headlines.tags' => 'required',
          'headlines.description' => 'required',

          'editorials.welcomePage' => '',
          'editorials.headline' => '',
          'editorials.choice' => '',
          'editorials.advertorial' => '',
          'editorials.source' => '',


      ])->merge($rules);
    }

}
