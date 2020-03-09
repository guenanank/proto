<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\ExtractImages::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        $schedule->command('extract:user')->everyMinute()->between('22:00', '23:00')->runInBackground();
        $schedule->command('extract:section')->everyMinute()->between('24:00', '00:00')->runInBackground();
        $schedule->command('extract:topic')->everyMinute()->between('10:00', '13:00')->runInBackground();
        $schedule->command('extract:podcast')->everyMinute()->between('2:00', '3:00')->runInBackground();
        $schedule->command('extract:music')->everyMinute()->between('3:00', '4:00')->runInBackground();
        $schedule->command('extract:video')->everyFifteenMinutes()->between('6:00', '17:00')->runInBackground();
        $schedule->command('extract:image')->everyFiveMinutes()->withoutOverlapping(5);
        $schedule->command('extract:article')->everyMinute()->withoutOverlapping(5);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return config('app.timezone');
    }
}
