@extends('layouts.app')

@section('page-title', 'Billing: Subscribe')

@section('content')

    @include('user.billing.tabs')

    <div class="row" id="subscription-form">
        <div class="col-md-6 mt-4">
            @include('user.billing.information')
        </div>

        <div class="col-md-6 mt-4">
            @if (! is_null($intent))
                @include('user.billing.plans')
            @endif

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Payment Information
                        </div>
                        <div class="card-body">
                            @if (! is_null($intent))
                                <!-- Stripe Elements Placeholder -->
                                <div id="card-element"></div>
                            @else
                                Unable to renew subscriptions at this time, please try again later.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (! is_null($intent))
                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-primary" id="card-button" data-secret="{{ $intent->client_secret }}" type="submit">Subscribe</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop

@section('pre-app-js')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script>
        window.stripe_key = "{{ config("services.stripe.key") }}";
    </script>
@stop
