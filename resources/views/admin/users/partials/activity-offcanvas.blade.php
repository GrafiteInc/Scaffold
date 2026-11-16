<div
    class="offcanvas offcanvas-start"
    id="userActivity"
    tabindex="-1"
    aria-labelledby="userActivityLabel"
    style="width: min(480px, 100vw);"
>
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title mb-0" id="userActivityLabel">Activity</h5>
            <div class="small text-body-secondary">{{ $user->name }} &middot; last 25</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        @if ($activities->isEmpty())
            <div class="text-center text-body-secondary p-5">
                <span class="fas fa-fw fa-clock-rotate-left fa-2x mb-3 d-block mx-auto"></span>
                No known activities
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach ($activities as $activity)
                    @php($detailId = 'activity-detail-'.$activity->id)
                    <div class="list-group-item p-0">
                        <button
                            class="activity-toggle btn w-100 text-start rounded-0 border-0 px-3 py-2 collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $detailId }}"
                            aria-expanded="false"
                            aria-controls="{{ $detailId }}"
                        >
                            <div class="d-flex align-items-start gap-2">
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between gap-2">
                                        <span class="fw-medium text-truncate">{{ $activity->description }}</span>
                                        <span
                                            class="small text-body-secondary text-nowrap"
                                            title="{{ $activity->created_at->format('M d, Y g:i:s A') }}"
                                        >{{ $activity->created_at->diffForHumans(short: true) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge {{ $activity->methodBadge() }}">{{ $activity->method() }}</span>
                                        <span class="small font-monospace text-body-secondary text-truncate">{{ $activity->path() }}</span>
                                    </div>
                                </div>
                                <span class="activity-chevron fas fa-fw fa-chevron-down small text-body-secondary mt-1"></span>
                            </div>
                        </button>

                        <div class="collapse" id="{{ $detailId }}">
                            <div class="bg-body-tertiary border-top px-3 py-3 small">
                                <dl class="row mb-0">
                                    <dt class="col-3 fw-normal text-body-secondary">Method</dt>
                                    <dd class="col-9 font-monospace">{{ $activity->method() }}</dd>

                                    <dt class="col-3 fw-normal text-body-secondary">URL</dt>
                                    <dd class="col-9 font-monospace text-break">{{ $activity->url() }}</dd>

                                    <dt class="col-3 fw-normal text-body-secondary">IP</dt>
                                    <dd class="col-9 font-monospace">{{ $activity->ip() ?? '—' }}</dd>

                                    <dt class="col-3 fw-normal text-body-secondary">Secure</dt>
                                    <dd class="col-9">
                                        @if ($activity->isSecure())
                                            <span class="badge bg-success">HTTPS</span>
                                        @else
                                            <span class="badge bg-secondary">HTTP</span>
                                        @endif
                                    </dd>

                                    <dt class="col-3 fw-normal text-body-secondary">Time</dt>
                                    <dd class="col-9 mb-0">{{ $activity->created_at->format('M d, Y g:i:s A') }}</dd>
                                </dl>

                                <div class="fw-semibold text-uppercase text-body-secondary mt-3 mb-2" style="font-size: .7rem; letter-spacing: .05em;">Payload</div>
                                @if (empty($activity->payload()))
                                    <div class="text-body-secondary fst-italic">No payload</div>
                                @else
                                    <div class="bg-body border rounded px-2 py-1">
                                        @include('admin.users.partials.activity-value', ['data' => $activity->payload()])
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@once
    @push('styles')
        <style>
            #userActivity .activity-toggle:hover { background-color: var(--bs-tertiary-bg); }
            #userActivity .activity-chevron { transition: transform .15s ease; }
            #userActivity .activity-toggle:not(.collapsed) .activity-chevron { transform: rotate(180deg); }
        </style>
    @endpush
@endonce
