<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use App\Scopes\SiteScope;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Channels extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['mediaId', 'name', 'slug', 'sub', 'isDisplayed', 'sort', 'meta', 'analytics', 'oId', 'creationDate', 'removedAt'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'isDisplayed' => 'boolean',
        // 'analytics' => 'collection',
        // 'meta' => 'collection',
        'sort' => 'int'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = ['media', 'parent', 'children'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    // public static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope(new SiteScope);
    // }

    /**
     * Get the channels that owns the parent.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\MongoDB\Channels', '_id', 'sub');
    }

    /**
     * Get the channels that owns the child.
     */
    public function children()
    {
        return $this->hasMany('App\Models\MongoDB\Channels', 'sub', '_id');
    }

    /**
     * Get the media that owns the channels.
     */
    public function media()
    {
        return $this->belongsTo('App\Models\MongoDB\Media', 'mediaId', '_id');
    }

    /**
     * Set the channel slug.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'name' => 'required|unique:channels,name|string|max:127',
          'sub' => 'exists:channels,id|nullable',
          'isDisplayed' => 'boolean',
          'sort' => 'numeric|nullable',

          'analytics.viewId' => 'nullable|alpha_num',

          'meta.title' => 'nullable|string',
          'meta.description' => 'nullable|string',
          'meta.keywords' => 'nullable|string',
          'meta.cover' => 'nullable|image'
      ])->merge($rules);
    }
}
