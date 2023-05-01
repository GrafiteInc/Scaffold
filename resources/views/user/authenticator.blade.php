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
        <div class="col-md-8">
            <p class="lead">Please scan the following QR code with your authenticator app of your choice, or enter in this code manually.</p>

            <div class="row">
                <div class="col-md-12 mt-5">
                    <label for="">Manual Authorization Code</label>
                    <input class="form-control" readonly value="{{ $manual }}" type="text">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="mt-5 d-flex justify-content-center">
                        {!! $code !!}
                    </div>
                </div>
            </div>

            @if ($recovery)
                <div class="row">
                    <div class="col-md-12 mt-5">
                        <label for="">Recovery Codes</label>
                        <br>
                        <small>You will never see these again, so keep them safe.</small>
                        <textarea class="form-control" rows="10">{!! Str::of($recovery)->replace(',', "\n") !!}</textarea>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop
