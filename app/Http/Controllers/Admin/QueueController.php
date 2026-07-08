<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * Display queue status and failed jobs.
     */
    public function index(): View
    {
        $schema = DB::connection()->getSchemaBuilder();

        $queuedJobsCount = $schema->hasTable('jobs')
            ? DB::table('jobs')->count()
            : 0;
        $queuedJobs = $schema->hasTable('jobs')
            ? DB::table('jobs')->orderBy('id')->limit(10)->get()
            : collect();

        $failedJobs = $schema->hasTable('failed_jobs')
            ? DB::table('failed_jobs')->orderBy('failed_at', 'desc')->get()
            : collect();

        return view('admin.queue.index')->with(compact('queuedJobsCount', 'queuedJobs', 'failedJobs'));
    }

    /**
     * Delete all queued jobs.
     */
    public function destroyAllQueued(): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('jobs')) {
            return redirect()->back()->withErrors('Jobs table is not available.');
        }

        $deletedCount = DB::table('jobs')->delete();

        if (! $deletedCount) {
            return redirect()->back()->withErrors('No queued jobs to delete.');
        }

        return redirect()->back()->with('message', 'Deleted all queued jobs.');
    }

    /**
     * Delete a queued job.
     */
    public function destroyQueued(int $queuedJob): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('jobs')) {
            return redirect()->back()->withErrors('Jobs table is not available.');
        }

        $deleted = DB::table('jobs')->where('id', $queuedJob)->delete();

        if (! $deleted) {
            return redirect()->back()->withErrors('Queued job was not found.');
        }

        return redirect()->back()->with('message', 'Queued job deleted.');
    }

    /**
     * Retry all failed jobs.
     */
    public function retryAll(): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('failed_jobs')) {
            return redirect()->back()->withErrors('Failed jobs table is not available.');
        }

        $failedJobIds = DB::table('failed_jobs')->orderBy('id')->pluck('id')->all();

        if (empty($failedJobIds)) {
            return redirect()->back()->withErrors('No failed jobs to retry.');
        }

        Artisan::call('queue:retry', [
            'id' => array_map(fn ($id) => (string) $id, $failedJobIds),
        ]);

        return redirect()->back()->with('message', 'Retrying all failed jobs.');
    }

    /**
     * Delete all failed jobs.
     */
    public function destroyAll(): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('failed_jobs')) {
            return redirect()->back()->withErrors('Failed jobs table is not available.');
        }

        $deletedCount = DB::table('failed_jobs')->delete();

        if (! $deletedCount) {
            return redirect()->back()->withErrors('No failed jobs to delete.');
        }

        return redirect()->back()->with('message', 'Deleted all failed jobs.');
    }

    /**
     * Retry a failed job.
     */
    public function retry(int $failedJob): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('failed_jobs')) {
            return redirect()->back()->withErrors('Failed jobs table is not available.');
        }

        $exists = DB::table('failed_jobs')->where('id', $failedJob)->exists();

        if (! $exists) {
            return redirect()->back()->withErrors('Failed job was not found.');
        }

        Artisan::call('queue:retry', [
            'id' => [(string) $failedJob],
        ]);

        return redirect()->back()->with('message', 'Failed job retry queued.');
    }

    /**
     * Delete a failed job.
     */
    public function destroy(int $failedJob): RedirectResponse
    {
        if (! DB::connection()->getSchemaBuilder()->hasTable('failed_jobs')) {
            return redirect()->back()->withErrors('Failed jobs table is not available.');
        }

        $deleted = DB::table('failed_jobs')->where('id', $failedJob)->delete();

        if (! $deleted) {
            return redirect()->back()->withErrors('Failed job was not found.');
        }

        return redirect()->back()->with('message', 'Failed job deleted.');
    }
}
