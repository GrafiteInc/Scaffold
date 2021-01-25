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
                    <a class="btn btn-block btn-outline-secondary mb-3" href="{{ route('user.settings') }}">Main Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <p>Please scan the following QR code with your authenticator App of choice, or enter in this code manually.</p>

            <div class="row">
                <div class="col-md-12 mt-5">
                    <input readonly value="{{ $manual }}" type="text">
                </div>
            </div>

            <div class="mt-5 d-flex justify-content-center">
                <img src="{{ $code }}">
            </div>
        </div>
    </div>

@stop
