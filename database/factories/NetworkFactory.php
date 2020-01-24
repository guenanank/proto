<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Networks;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Networks::class, function (Faker $faker) {
    return [
        'name' => $faker->catchPhrase,
        'description' => $faker->text
    ];
});
