<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Pimlie\DataTables\Traits\MongodbDataTableTrait;

class MongoDB extends Model
{
    use MongodbDataTableTrait;

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
}
