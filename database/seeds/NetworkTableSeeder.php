<?php

use Illuminate\Database\Seeder;

class NetworkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Networks::class, 4)->create();
        
    }
}
