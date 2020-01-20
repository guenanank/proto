<?php

namespace App\Models;

use App\Models;
use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Topics extends Models
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['site_id', 'title', 'slug', 'published', 'meta'];

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
     * Get the topic that owns the site.
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Sites');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'site_id' => 'exists:sites,id|nullable',
          'title' => 'required|unique:topics,title,NULL,id,deleted_at,NULL|string|max:127',
          'published' => 'date_format:Y-m-d H:i:s|nullable'
      ])->merge($rules);
    }
}
