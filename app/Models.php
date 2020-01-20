<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class Models extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published', 'created_at', 'updated_at', 'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();
        // static::updating(function ($model) {
            // do some logging
            // override some property like $model->something = transform($something);
        // });

        // static::addGlobalScope('order', function (Builder $builder) {
        //     $builder->orderBy('name', 'asc');
        // });
    }

}