<?php

use Illuminate\Database\Seeder;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Sources::class, 700)->create();
    }
}
