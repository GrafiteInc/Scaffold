@extends('layouts.user')

@section('page-title', 'Security')

@section('user_content')
    @if (request()->user()->hasUnconfirmedTwoFactor())
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-body bg-warning shadow-sm border-0 text-center">
                    You have not confirmed your Two Factor authenticator.
                </div>
            </div>
        </div>
    @endif
    <div class="row mt-3">
        <div class="col-md-4">
            <x-f-base :form="\App\View\Forms\LogoutForm::class" />
            @if (auth()->user()->usesTwoFactor('authenticator'))
                <a class="btn d-block w-100 btn-outline-secondary mb-3" href="{{ route('user.security.two-factor') }}">
                    <span class="fas fa-fw fa-shield-alt"></span>
                    @if (request()->user()->hasUnconfirmedTwoFactor())
                        Confirm Two Factor
                    @else
                        Recovery Codes
                    @endif
                </a>
            @endif
        </div>
        <div class="col-md-8">
            <h4>Multifactor Authentication</h4>
            <hr>
            <x-f-base :form="\App\View\Forms\UserTwoFactorForm::class" />
            <h4 class="mt-4">Change Password</h4>
            <hr>
            <x-f-base :form="\App\View\Forms\UserPasswordForm::class" />
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12 border-top pt-4">
            <h4 class="mb-4">Devices</h4>
            @include('user.devices', ['sessions' => $user->getDevices()])
        </div>
    </div>

@stop
