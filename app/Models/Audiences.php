<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audiences extends Models
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['username', 'email', 'profile'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'profile' => 'object'
    ];

    public static function rules(array $rules = [])
    {
        return collect([
          'name' => 'required|unique:sites,name|string|max:63',
          'domain' => 'required|url|max:127'
      ])->merge($rules);
    }
}
