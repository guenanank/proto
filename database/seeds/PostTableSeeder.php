<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run(Faker $faker)
     {
         foreach (range(0, 1000) as $i) {
             DB::table('posts')->insert([
                  'site_id' => rand(1, 5),
                  'channel_id' => rand(0, 50),
                  'type' => array_rand(['articles','images','videos','recipes','podcasts','pricelists','charts','pollings']),
                  'headline' => [
                    'title' => $faker->title,
                    'description' => $faker->text,
                    'tags' => $faker->words
                  ],
                  'editorials' => [
                    'headline' => rand(0, 1),
                    'stories' => rand(0, 1)
                  ],
                  'published' => $faker->date . ' ' . $faker->time,
                  'body' => $faker->realText,
                  'media' => [],
                  'reporter' => [
                    'name' => $faker->name
                  ],
                  'editor' => [
                    'name' => $faker->name
                  ],
                  'commentable' => true,
                  'analytics' => []
              ]);
         }
     }
}
