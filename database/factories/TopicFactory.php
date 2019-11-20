<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Topics;
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

$factory->define(Topics::class, function (Faker $faker) {
    return [
      'title' => $faker->sentence,
      'slug' => $faker->slug,
      'published' => $faker->date . ' ' . $faker->time,
      'meta' => [
        'description' => $faker->paragraph
      ],
    ];
});
