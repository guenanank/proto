<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class GalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        foreach (range(0, 1000) as $i) {
            DB::table('galleries')->insert([
                 'site_id' => rand(1, 5),
                 'type' => array_rand(['images', 'videos', 'podcasts']),
                 'meta' => []
             ]);
        }
    }
}
