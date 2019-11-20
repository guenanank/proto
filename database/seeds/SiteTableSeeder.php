<?php

use Illuminate\Database\Seeder;

class SiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Sites::class, 5)->create()->each(function ($site) {
            for ($i = 0; $i < 10; $i++) {
                $site->channels()->save(factory(App\Models\Channels::class)->make());
            }

            for ($i = 0; $i < 25; $i++) {
                $site->topics()->save(factory(App\Models\Topics::class)->make());
            }

            for ($i = 0; $i < 500; $i++) {
                $site->galleries()->save(factory(App\Models\Galleries::class)->make());
            }
        });
    }
}
