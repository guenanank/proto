<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Galleries;
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

$factory->define(Galleries::class, function (Faker $faker) {
    $type = ['images', 'videos', 'podcasts'];
    return [
      'type' => $type[array_rand($type)],
      'meta' => [
          'title' => $faker->catchPhrase,
          'caption' => $faker->catchPhrase,
          'description' => $faker->text,
          'filename' => $faker->imageUrl(rand(320, 960), rand(320, 960)),
          'extension' => $faker->fileExtension,
          'photographer' => $faker->name,
          'director' => $faker->name,
          'embed' => ''
      ]
    ];
});
