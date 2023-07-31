<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('mission-control:report')->everyFiveMinutes()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        $schedule->command('db:table-empty', ['failed_jobs', '30'])->dailyAt('2:45')
            ->runInBackground()
            ->after(function () {
                mission_control_notify('Emptied the Failed Jobs table', 'maintenance');
            });

        $schedule->command('maintenance:gzip-purge')->monthlyOn(4, '4:45')
            ->runInBackground()
            ->after(function () {
                mission_control_notify('Purged gzipped logs', 'maintenance');
            });

        $schedule->command('maintenance:php-outdated')
            ->monthlyOn(4, '4:45')
            ->after(function (Stringable $output) {
                mission_control_notify('PHP Outdated Parsing', 'maintenance', $output);
            });

        $schedule->command('maintenance:js-outdated')
            ->monthlyOn(4, '4:45')
            ->runInBackground()
            ->after(function (Stringable $output) {
                mission_control_notify('JS Outdated Parsing', 'maintenance', $output);
            });
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
}
