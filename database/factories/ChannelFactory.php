<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Channels;
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

$factory->define(Channels::class, function (Faker $faker) {
    return [
      'name' => $faker->domainWord,
      'slug' => $faker->slug,
      'sub' => rand(5, 45),
      'displayed' => rand(0, 1),
      'sort' => rand(1, 9),
      'meta' => [
          'title' => $faker->catchPhrase,
          'description' => $faker->text,
          'keywords' => $faker->words,
          'logo' => '',
      ],
      'analytics' => [
          'ga_id' => $faker->swiftBicNumber,
          'youtube_id' => $faker->iban
      ]
    ];
});
