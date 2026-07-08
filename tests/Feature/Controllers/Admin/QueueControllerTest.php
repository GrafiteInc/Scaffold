<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\FailedJob;
use App\Models\Job;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QueueControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();

        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }
    }

    public function test_index_shows_queued_and_failed_job_counts()
    {
        Job::factory()->count(2)->create();
        FailedJob::factory()->create([
            'exception' => 'Index failure',
        ]);

        $response = $this->get(route('admin.queue.index'));

        $response->assertOk();
        $response->assertSee('Queued Jobs: 2');
        $response->assertSee('Failed Jobs: 1');
        $response->assertSee('Index failure');
    }

    public function test_index_lists_only_next_ten_queued_jobs()
    {
        foreach (range(1, 11) as $index) {
            Job::factory()->create([
                'payload' => '{"displayName":"Queued-'.$index.'"}',
            ]);
        }

        $response = $this->get(route('admin.queue.index'));

        $response->assertOk();
        $response->assertSee('Queued-1');
        $response->assertSee('Queued-10');
        $response->assertDontSee('Queued-11');
    }

    public function test_retry_all_retries_failed_jobs_in_deterministic_id_order()
    {
        FailedJob::factory()->create([
            'queue' => 'default',
            'exception' => 'First failure',
        ]);
        FailedJob::factory()->create([
            'queue' => 'emails',
            'exception' => 'Second failure',
        ]);

        $failedJobIds = array_map(
            fn ($id) => (string) $id,
            FailedJob::query()->orderBy('id')->pluck('id')->all()
        );

        Artisan::spy();

        $response = $this->post(route('admin.queue.retry-all'));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Retrying all failed jobs.');

        Artisan::shouldHaveReceived('call')
            ->once()
            ->with('queue:retry', [
                'id' => $failedJobIds,
            ]);
    }

    public function test_retry_retries_single_failed_job_by_uuid()
    {
        $failedJob = FailedJob::factory()->create([
            'uuid' => 'f3a4526e-98fa-4fb4-8d8b-2d90c3cd12e7',
        ]);

        Artisan::spy();

        $response = $this->post(route('admin.queue.retry', [$failedJob->uuid]));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Failed job retry queued.');

        Artisan::shouldHaveReceived('call')
            ->once()
            ->with('queue:retry', [
                'id' => [$failedJob->uuid],
            ]);
    }

    public function test_destroy_queued_job_removes_single_job()
    {
        $job = Job::factory()->create();

        $response = $this->delete(route('admin.queue.queued.destroy', [$job->id]));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Queued job deleted.');
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
    }

    public function test_destroy_all_queued_jobs_removes_all_jobs()
    {
        Job::factory()->count(3)->create();

        $response = $this->delete(route('admin.queue.queued.destroy-all'));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Deleted all queued jobs.');
        $this->assertDatabaseCount('jobs', 0);
    }
}
