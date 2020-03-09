<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Groups extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['code', 'name', 'analytics', 'meta'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'analytics' => 'json',
        // 'meta' => 'json'
    ];

    /**
     * Set the group's code.
     *
     * @param  string  $value
     * @return void
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Get the media that owns the group.
     */
    public function media()
    {
        return $this->hasMany('App\Models\MongoDB\Media', 'groupId', '_id');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'code' => 'present|alpha_num|max:15',
          'name' => 'required|string|max:63',

          'analytics.gaId' => 'nullable|alpha_num',

          'meta.title' => 'nullable',
          'meta.description' => 'nullable|string',
          'meta.privacy' => 'nullable|string',
          'meta.guideline' => 'nullable|string',
      ])->merge($rules);
    }
}
