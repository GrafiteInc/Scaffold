<div class="mx-4 mb-3">
    <h4 class="m-0">
        <span class="fa fa-fw fa-cogs"></span>
        <span class="ps-2">{{ config('app.name') }}</span>
    </h4>
</div>

<ul class="nav flex-column">
    <x-app-subnav-contents></x-app-subnav-contents>
</ul>

@if (auth()->user()->onTrial())
<div class="position-fixed bottom-0 bg-warning-subtle text-warning-emphasis border border-start-0 border-warning-subtle rounded-end mb-4">
    <p class="p-4 mb-0">
        You are currently on trial mode.
        <br>This will expire in {{ floor(auth()->user()->trial_ends_at->diffInDays(now(), true)) }} days.
        <br>
        <br>
        <a class="fw-semibold text-reset" href="{{ route('user.billing') }}">Subscribe here</a>
    </p>
</div>
@endif
