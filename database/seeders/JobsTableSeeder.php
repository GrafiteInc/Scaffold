<?php

namespace Database\Seeders;

use App\Models\FailedJob;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('jobs')) {
            Job::factory()->count(10)->create();
        }

        if (Schema::hasTable('failed_jobs')) {
            FailedJob::factory()->count(5)->create();
        }
    }
}
