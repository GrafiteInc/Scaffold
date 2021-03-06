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
                            <input id="passcode" class="form-control" name="one_time_password" type="text" required>

                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <div class="btn-toolbar justify-content-end">
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
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif
@stop
