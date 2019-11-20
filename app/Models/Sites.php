<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sites extends Models
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['name', 'domain', 'analytics', 'meta'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'analytics' => 'object',
        'meta' => 'object'
    ];

    /**
     * Get the site that owns the channels.
     */
    public function channels()
    {
        return $this->hasMany('App\Models\Channels', 'site_id', 'id');
    }

    /**
     * Get the site that owns the topics.
     */
    public function topics()
    {
        return $this->hasMany('App\Models\Topics', 'site_id', 'id');
    }

    /**
     * Get the site that owns the galleries.
     */
    public function galleries()
    {
        return $this->hasMany('App\Models\Galleries', 'site_id', 'id');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'name' => 'required|unique:sites,name|string|max:63',
          'domain' => 'required|url|max:127'
      ])->merge($rules);
    }
}
