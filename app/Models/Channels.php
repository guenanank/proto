<?php

namespace App\Models;

use App\Models;
use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;

class Channels extends Models
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['site_id', 'name', 'slug', 'sub', 'displayed', 'sort', 'meta', 'analytics'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'displayed' => 'boolean',
        'analytics' => 'object',
        'meta' => 'object'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['site:id,name,domain'];

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
     * Get the channels that owns the parent.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Channels', 'id', 'sub');
    }

    /**
     * Get the channels that owns the child.
     */
    public function children()
    {
        return $this->hasMany('App\Models\Channels', 'sub', 'id');
    }

    /**
     * Get the topic that owns the site.
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Sites');
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
            'site_id' => 'exists:sites.id|nullable',
            'name' => 'required|unique:channels,name|string|max:127',
            'sub' => 'exists:channels,id|nullable',
            'displayed' => 'boolean',
            'sort' => 'numeric|nullable',
            'cover' => 'image'
        ])->merge($rules);
    }
}
