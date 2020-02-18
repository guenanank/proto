<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Sites;
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

$factory->define(Sites::class, function (Faker $faker) {
    return [
      'network_id' =>rand(1,4),
      'name' => $faker->domainWord,
      'domain' => $faker->domainName,
      'footer' => [
          'about_us' => $faker->text,
          'editorial' => $faker->text,
          'management' => $faker->text,
      ],
      'meta' => [
          'title' => $faker->catchPhrase,
          'alias' => $faker->domainWord,
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
