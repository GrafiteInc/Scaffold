<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Stringable;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands and the application's command schedule.
|
*/

Schedule::command('mission-control:report')->everyFiveMinutes()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

Schedule::command('mission-control:stats')->dailyAt('9:00')
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

// Enable if you have queue workers
// Schedule::command('mission-control:queue')->everyFifteenMinutes()
//     ->runInBackground()
//     ->appendOutputTo(storage_path('logs/scheduler.log'));

// Enable if jobs are discardable
// Schedule::command('db:table-empty', ['failed_jobs', '30'])->dailyAt('2:45')
//     ->runInBackground()
//     ->after(function () {
//         mission_control_notify('Emptied the Failed Jobs table', 'maintenance');
//     })
//     ->onOneServer();

Schedule::command('maintenance:gzip-purge')->monthlyOn(4, '4:45')
    ->runInBackground()
    ->after(function () {
        mission_control_notify('Purged gzipped logs', 'maintenance');
    });

Schedule::command('maintenance:php-outdated')
    ->monthlyOn(4, '4:45')
    ->after(function (Stringable $output) {
        mission_control_notify('PHP Outdated Parsing', 'maintenance', $output);
    })
    ->onOneServer();

Schedule::command('maintenance:js-outdated')
    ->monthlyOn(4, '4:45')
    ->runInBackground()
    ->after(function (Stringable $output) {
        mission_control_notify('JS Outdated Parsing', 'maintenance', $output);
    })
    ->onOneServer();
