@extends('layouts.guest')

@section('page-title', 'Verify Email Address')

@section('content')

    <div class="content-sm mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }}:
                        <br>
                        <br>
                        {!! form()->action('post', 'verification.resend', 'Click here to request another') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('alerts')
    @if (session('resent'))
        <div class="alert bg-info" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@stop
