<?php

namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Networks extends Models
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function sites()
    {
        return $this->hasMany('App\Models\Sites', 'network_id', 'id');
    }

    public static function rules(array $rules = [])
    {
        return collect([
          'name' => 'required|unique:networks,name|string|max:100'
      ])->merge($rules);
    }
}
