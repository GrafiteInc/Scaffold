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
<div class="position-fixed bottom-0 bg-warning rounded-end mb-4">
    <p class="p-4 mb-0">
        You are currently on trial mode.
        <br>This will expire in {{ auth()->user()->trial_ends_at->diffInDays() }} days.
        <br>
        <br>
        <a class="text-white" href="{{ route('user.billing') }}">Subscribe here</a>
    </p>
</div>
@endif
