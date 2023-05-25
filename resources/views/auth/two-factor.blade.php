@extends('layouts.guest')

@section('page-title', 'Two Factor Authentication')

@section('content')

    <div class="content-sm mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">{{ __('Two Factor Authentication') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('verification.two-factor') }}">
                            {!! csrf_field() !!}

                            <label for="passcode">One Time Password</label>
                            <input id="passcode" class="form-control mt-3" name="one_time_password" type="text" required>

                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <div class="btn-toolbar justify-content-between">
                                        <a class="btn btn-outline-secondary" href="{{ route('recovery') }}">Use Recovery Codes</a>
                                        <button class="btn btn-primary" type="submit">Authenticate</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
