@extends('layouts.guest')

@section('page-title', 'Early Access')

{{--
    Early-access landing page. Shown at /waitlist, and at / when WAITLIST=true.
    The copy below is placeholder: replace the headline, subhead and features per project.
--}}

@section('content')
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge rounded-pill text-bg-primary bg-opacity-75 mb-3">
                    <span class="fas fa-fw fa-flask"></span> Now in private beta
                </span>
                <h1 class="display-5 fw-bold mb-3">{{ config('app.name') }} is almost here.</h1>
                <p class="lead text-body-secondary mb-0">
                    We're opening the doors gradually. Join the waitlist to get early access,
                    help shape what we build, and be the first to know when your spot is ready.
                </p>
            </div>

            <div class="col-lg-5">
                <div class="card shadow border-0" id="waitlist">
                    <div class="card-body p-4">
                        @if (session('waitlist_joined'))
                            <div class="text-center py-3" role="status">
                                <span class="fas fa-3x fa-circle-check text-success mb-3"></span>
                                <h2 class="h4">You're on the list!</h2>
                                <p class="text-body-secondary mb-0">
                                    Check your inbox for a confirmation. We'll email you an invite as soon as a spot opens up.
                                </p>
                            </div>
                        @else
                            <h2 class="h4 mb-1">Get early access</h2>
                            <p class="text-body-secondary small mb-3">No spam, just one email when it's your turn.</p>
                            <x-f-base :form="\App\View\Forms\WaitlistForm::class" />
                        @endif
                    </div>
                </div>
                @guest
                    <p class="text-center small text-body-secondary mt-3 mb-0">
                        Already have an invite or account? <a href="{{ route('login') }}">Log in</a>
                    </p>
                @endguest
            </div>
        </div>

        <div class="row g-4 mt-5 pt-lg-4">
            @foreach ([
                ['icon' => 'fa-bolt', 'title' => 'Fast to start', 'body' => 'A sentence about the first thing people get out of the app.'],
                ['icon' => 'fa-people-group', 'title' => 'Built for teams', 'body' => 'A sentence about collaboration, sharing or working together.'],
                ['icon' => 'fa-shield-halved', 'title' => 'Secure by default', 'body' => 'A sentence about privacy, security or reliability.'],
            ] as $feature)
                <div class="col-md-4">
                    <div class="h-100 p-4 rounded bg-body-tertiary">
                        <span class="fas fa-fw fa-lg {{ $feature['icon'] }} text-primary mb-3"></span>
                        <h3 class="h5">{{ $feature['title'] }}</h3>
                        <p class="text-body-secondary mb-0">{{ $feature['body'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
