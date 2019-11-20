<?php

use Illuminate\Database\Seeder;

class AudienceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Audiences::class, 500)->create();
    }
}
