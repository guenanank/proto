<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class MongoDB extends Moloquent
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    // protected $connection = 'mongodb';

    const CREATED_AT = 'creationDate';
    const UPDATED_AT = 'lastUpdate';
    const DELETED_AT = 'removedAt';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published', 'creationDate', 'lastUpdate', 'removedAt'
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
<<<<<<< HEAD:app/MongoDB.php
}
=======

}
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b:app/Models.php
