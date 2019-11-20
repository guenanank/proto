<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Audiences;
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

$factory->define(Audiences::class, function (Faker $faker) {
    return [
      'username' => $faker->username,
      'email' => $faker->unique()->safeEmail,
      'profile' => [
        'fullname' => $faker->name,
        'address' => $faker->address,
        'phone' => $faker->tollFreePhoneNumber,
        'avatar' => $faker->imageUrl(100, 100, 'people', true, 'Faker')
      ]
    ];
});
