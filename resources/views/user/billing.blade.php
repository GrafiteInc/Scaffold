@extends('layouts.user')

@section('page-title', 'Billing')

@section('user_content')
    <div class="row mt-4">
        <div class="col-md-6">
            @if ($user->hasActiveSubscription())
                <div class="card mb-4 border-0">
                    <div class="card-body bg-body-tertiary border-start bmx-border-green bmx-border-6 shadow-sm rounded">
                        <h5>Current Plan:</h5>
                        <p>{{ $user->subscriptionPlan('name') }}</p>
                        <h5>Upcoming Payment:</h5>
                        <p class="mb-0">{{ $upcomingPayment->total() }} on {{ $upcomingPayment->date()->format('M jS, Y') }}</p>
                    </div>
                </div>
            @endif
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-body-tertiary bmx-border-6 shadow-sm rounded">
                    <h5 class="mb-4">Billing Information</h5>
                    {!! $form !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @if ($user->hasDefaultPaymentMethod())
                @if (! $user->hasActiveSubscription())
                    <div class="card mb-4 border-0">
                        <div class="card-body bg-body-tertiary border-start bmx-border-green bmx-border-6 shadow-sm rounded">
                            {!! $subscribeForm !!}
                        </div>
                    </div>
                @else
                    <div class="card mb-4 border-0">
                        <div class="card-body bg-body-tertiary border-start bmx-border-gray-300 bmx-border-6 shadow-sm rounded">
                            {!! $couponForm !!}
                        </div>
                    </div>
                    <div class="card mb-4 border-0">
                        <div class="card-body bg-body-tertiary border-start bmx-border-cyan bmx-border-6 shadow-sm rounded">
                            {!! $swapForm !!}
                        </div>
                    </div>
                @endif
            @else
                <div class="card mb-4 border-0">
                    <div class="card-body bg-body-tertiary border-start bmx-border-red bmx-border-6 shadow-sm rounded text-center">
                        <p class="m-0">Please add a Payment Method in your Billing Portal</p>
                    </div>
                </div>
            @endif
            <div class="card mb-4 border-0">
                <div class="card-body bg-body-tertiary border-start bmx-border-purple bmx-border-6 shadow-sm rounded">
                    @if ($user->hasBillingInformation())
                        <p>Please access your billing portal to handle the following:</p>
                        <ul>
                            <li>Payment methods</li>
                            <li>Cancel your subscription</li>
                            <li>Renew your subscription</li>
                            <li>View subscription invoices</li>
                        </ul>
                        <a href="{{ $user->billingPortalUrl(route('user.billing')) }}" class="btn btn-outline-primary mt-4 w-100">My Billing Portal</a>
                    @else
                        <p class="m-0">In order to access your billing portal, please update your billing information.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
