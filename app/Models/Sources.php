<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sources extends Models
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object'
    ];
}
