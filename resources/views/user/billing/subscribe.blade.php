@extends('layouts.dashboard')

@section('page-title') Billing: Subscribe @stop

@section('content')

    @include('user.billing.tabs')

    <div class="row" id="subscription-form">
        <div class="col-md-6 mt-4">
            @include('user.billing.information')
        </div>

        <div class="col-md-6 mt-4">
            @include('user.billing.plans')

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Payment Information
                        </div>
                        <div class="card-body">
                            <!-- Stripe Elements Placeholder -->
                            <div id="card-element"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-right">
                <div class="col-md-12">
                    <button class="btn btn-primary" id="card-button" data-secret="{{ $intent->client_secret }}" type="submit">Subscribe</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('pre-app-js')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script>
        window.stripe_key = "{{ config("services.stripe.key") }}";
        window.dark_mode = "{{ json_encode((bool) auth()->user()->dark_mode) }}"
    </script>
@stop
