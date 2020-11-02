@extends('layouts.master')

@section('page-title', 'Verify Email Address')

@section('app-content')

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
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif
@stop
