@extends('layouts.user')

@section('page-title', 'Authenticator QR Code')

@section('user_content')

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-8">
                    @if (auth()->user()->avatar)
                        <div class="mb-4 d-flex justify-content-center">
                            <div class="avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
                        </div>
                    @endif
                    <a class="btn w-100 btn-outline-secondary mb-3" href="{{ route('user.settings') }}">Main Settings</a>
                </div>
            </div>
        </div>

        @if (! auth()->user()->hasConfirmedAuthenticator())
            <div class="col-md-8">
                <p class="lead">Please scan the following QR code with your authenticator app of your choice, or enter in this code manually.</p>

                <div class="row">
                    <div class="col-md-12 mt-4">
                        <label for="">Manual Authorization Code</label>
                        <input class="form-control" readonly value="{{ $manual }}" type="text">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="mt-5 d-flex justify-content-center">
                            {!! $code !!}
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <x-f-base :form="\App\View\Forms\ConfirmTwoFactorForm::class" />
                    </div>
                </div>
            @endif

            @if (auth()->user()->hasConfirmedAuthenticator())
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="lead">Recovery Codes</p>
                            <small>These can disable MFA, so please keep them safe.</small>
                            <code><pre class="mt-4 border rounded">{!! Str::of(auth()->user()->two_factor_recovery_codes)->replace(',', "\n") !!}</pre></code>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop
