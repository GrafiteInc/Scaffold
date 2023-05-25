@extends('layouts.guest')

@section('page-title', 'Recovery Authentication')

@section('content')
    <div class="content-sm mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">{{ __('Two Factor Authentication') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('recovery.verify') }}">
                            {!! csrf_field() !!}

                            <label for="email">Email</label>
                            <input id="email" class="form-control mt-3 mb-3" name="email" type="email" required>

                            <label for="recovery">Recovery Code</label>
                            <input id="recovery" class="form-control mt-3" name="recovery_code" type="text" required>

                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <div class="btn-toolbar justify-content-end">
                                        <button class="btn btn-primary" type="submit">Recover Account</button>
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
