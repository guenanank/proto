<?php

namespace App\Models\MongoDB;

use App\MongoDB;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Sources extends MongoDB
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['meta'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'meta' => 'collection'
    ];

    public static function rules(array $rules = [])
    {
        return collect([
          'meta.name' => 'nullable|string',
          'meta.title' => 'nullable|string',
          'meta.url' => 'nullable|string'
      ])->merge($rules);
    }
}
