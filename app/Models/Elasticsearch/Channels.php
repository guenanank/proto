<?php

namespace App\Models\Elasticsearch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use Basemkhirat\Elasticsearch\Model;

class Channels extends Model
{
    protected $index = 'channels';
    protected $type = 'posts';

    /**
     * The primary key associated with the index.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'displayed' => 'boolean',
    ];

    /**
     * Set the channel slug.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
        return $value;
    }

    /**
     * Scope a query to only include shown channels.
     *
     * @param \Basemkhirat\Elasticsearch\Query $query
     * @return \Basemkhirat\Elasticsearch\Query
     */
    public function scopeShown($query)
    {
        return $query->where('displayed', true);
    }

    /**
     * Scope a query to only include hidden channels.
     *
     * @param \Basemkhirat\Elasticsearch\Query $query
     * @return \Basemkhirat\Elasticsearch\Query
     */
    public function scopeHidden($query)
    {
        return $query->where('displayed', false);
    }

    public static function rules(array $rules = [])
    {
        return collect([
            'name' => 'required|string|max:127',
            'sub' => 'nullable',
            'displayed' => 'boolean',
            'sort' => 'digits|nullable',
            'ga_id' => 'max:31|nullable'
        ])->merge($rules);
    }
}
