<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Media extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['groupId', 'name', 'domain', 'analytics', 'meta', 'assets', 'masthead', 'oId'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'analytics' => 'collection',
        'meta' => 'collection',
        'assets' => 'collection',
        'masthead' => 'collection'
    ];

    /**
     * Get the group that owns the media.
     */
    public function group()
    {
        return $this->belongsTo('App\Models\MongoDB\Groups', 'groupId', '_id');
    }

    /**
     * Get the media that owns the channels.
     */
    public function channels()
    {
        return $this->hasMany('App\Models\MongoDB\Channels', 'mediaId', '_id');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'name' => 'required|unique:sites,name|string|max:63',
          'domain' => 'required|url|max:127',

          'analytics.gaId' => 'nullable|alpha_num',
          'analytics.youtubeChannel' => 'nullable|string',

          'meta.title' => 'nullable|string|max:163',
          'meta.keywords' => 'nullable',
          'meta.color' => 'nullable|string|max:163',
          'meta.description' => 'nullable|string',

          'assets.logo' => 'nullable',
          'assets.logoAlt' => 'nullable',
          'assets.icon' => 'nullable',
          'assets.css' => 'nullable|array',
          'assets.js' => 'nullable|array',

          'masthead.about' => 'nullable',
          'masthead.editorial' => 'nullable',
          'masthead.management' => 'nullable',
          'masthead.contact' => 'nullable'
      ])->merge($rules);
    }
}
