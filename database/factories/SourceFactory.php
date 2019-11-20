<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Sources;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Sources::class, function (Faker $faker) {
    return [
      'data' => [
          'name' => $faker->colorName,
          'title' => $faker->catchPhrase,
          'url' => $faker->url
      ]
    ];
});
