@extends('layouts.admin')

@section('page-title', 'Admin: Queue')

@section('admin_content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3 rounded p-1 bg-body-tertiary" id="queueSubnav" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="queued-jobs-tab" data-bs-toggle="pill" data-bs-target="#queued-jobs-panel" type="button" role="tab" aria-controls="queued-jobs-panel" aria-selected="true">
                        Queued Jobs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="failed-jobs-tab" data-bs-toggle="pill" data-bs-target="#failed-jobs-panel" type="button" role="tab" aria-controls="failed-jobs-panel" aria-selected="false">
                        Failed Jobs
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="queueSubnavContent">
                <div class="tab-pane fade show active" id="queued-jobs-panel" role="tabpanel" aria-labelledby="queued-jobs-tab" tabindex="0">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">Queue Overview</div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                    <p class="mb-0">Queued Jobs: {{ $queuedJobsCount }}</p>

                                    @if ($queuedJobsCount > 0)
                                        {!! form()->confirm('Are you sure you want to delete all queued jobs?', 'app.confirmation')
                                            ->action('delete',
                                            ['admin.queue.queued.destroy-all'],
                                            'Delete All',
                                            ['class' => 'btn btn-sm btn-outline-danger']
                                        ) !!}
                                    @endif
                                </div>
                            </div>
                        </div>

                    <div class="card shadow-sm mb-3">
                        <div class="card-header">Next 10 Queued Jobs</div>
                        <div class="card-body">
                            @if ($queuedJobs->isEmpty())
                                <p class="mb-0">No queued jobs.</p>
                            @else
                                <div class="w-100">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Queue</th>
                                                <th>Display Name</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($queuedJobs as $job)
                                                @php
                                                    $payload = json_decode($job->payload, true);
                                                    $displayName = data_get($payload, 'displayName', 'N/A');
                                                @endphp
                                                <tr>
                                                    <td>{{ $job->id }}</td>
                                                    <td>{{ $job->queue }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit((string) $displayName, 80) }}</td>
                                                    <td class="text-end">
                                                        {!! form()->confirm('Are you sure you want to delete queued job #'.$job->id.'?', 'app.confirmation')
                                                            ->action('delete',
                                                            ['admin.queue.queued.destroy', $job->id],
                                                            'Delete',
                                                            ['class' => 'btn btn-sm btn-outline-danger']
                                                        ) !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="failed-jobs-panel" role="tabpanel" aria-labelledby="failed-jobs-tab" tabindex="0">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">Failed Jobs Overview</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                <p class="mb-0">Failed Jobs: {{ $failedJobs->count() }}</p>

                                @if ($failedJobs->count() > 0)
                                    <div class="d-flex gap-2">
                                        {!! form()->action('post',
                                            ['admin.queue.retry-all'],
                                            'Retry All',
                                            ['class' => 'btn btn-sm btn-outline-primary']
                                        ) !!}

                                        {!! form()->confirm('Are you sure you want to delete all failed jobs?', 'app.confirmation')
                                            ->action('delete',
                                            ['admin.queue.destroy-all'],
                                            'Delete All',
                                            ['class' => 'btn btn-sm btn-outline-danger']
                                        ) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header">Latest Failed Jobs</div>
                        <div class="card-body">
                            @if ($failedJobs->isEmpty())
                                <p class="mb-0">No failed jobs.</p>
                            @else
                                <div class="w-100">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Queue</th>
                                                <th>Display Name</th>
                                                <th>Exception</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($failedJobs as $job)
                                                @php
                                                    $payload = json_decode($job->payload, true);
                                                    $displayName = data_get($payload, 'displayName', data_get($payload, 'data.commandName', 'N/A'));
                                                @endphp
                                                <tr>
                                                    <td>{{ $job->id }}</td>
                                                    <td>{{ $job->queue }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit((string) $displayName, 80) }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit((string) $job->exception, 120) }}</td>
                                                    <td class="text-end">
                                                        <div class="d-inline-flex gap-2">
                                                            {!! form()->action('post',
                                                                ['admin.queue.retry', $job->id],
                                                                'Retry',
                                                                ['class' => 'btn btn-sm btn-outline-primary']
                                                            ) !!}

                                                            {!! form()->confirm('Are you sure you want to delete failed job #'.$job->id.'?', 'app.confirmation')
                                                                ->action('delete',
                                                                ['admin.queue.destroy', $job->id],
                                                                'Delete',
                                                                ['class' => 'btn btn-sm btn-outline-danger']
                                                            ) !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
