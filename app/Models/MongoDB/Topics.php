<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use App\Scopes\SiteScope;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Topics extends MongoDB
{
    use SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'oId';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['mediaId', 'title', 'slug', 'published', 'meta', 'oId', 'creationDate', 'removedAt'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'meta' => 'collection'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['media'];

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
     * Set the topic slug.
     *
     * @param  string  $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the media that owns the topics.
     */
    public function media()
    {
        return $this->belongsTo('App\Models\MongoDB\Media', 'mediaId', '_id');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'title' => 'required|string|max:255',
          'meta.description' => 'nullable|string',
          'meta.isBrandstory' => 'nullable|bool',
          'meta.cover' => 'nullable|string'
      ])->merge($rules);
    }
}
