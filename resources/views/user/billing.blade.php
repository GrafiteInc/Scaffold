@extends('layouts.user')

@section('page-title', 'Billing')

@section('user_content')
    <div class="row mt-4">
        @if ($user->hasActiveSubscription())
            <div class="col-md-6">
                <div class="card mb-4 border-0">
                    <div class="card-body bg-body-tertiary border-start bmx-border-green bmx-border-6 shadow-sm rounded">
                        <h5>Current Plan:</h5>
                        <p>{{ $user->subscriptionPlan('name') }}</p>
                        <h5>Current Payment Method:</h5>
                        <p>{{ strtoupper($user->pm_type) }}</p>
                        <p>**** **** **** {{ $user->pm_last_four }}</p>
                        @if ($upcomingPayment)
                            <h5>Upcoming Payment:</h5>
                            <p class="mb-0">{{ $upcomingPayment->total() }} on {{ $upcomingPayment->date()->format('M jS, Y') }}</p>
                        @else
                            <h5>Ends At:</h5>
                            <p class="mb-0">{{ $user->subscription(config('billing.subscription_name'))->ends_at->format('M jS, Y \a\t h:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4 border-0">
                    <div class="card-body bg-body-tertiary border-start bmx-border-purple bmx-border-6 shadow-sm rounded">
                        <p>Please access your billing portal to handle the following:</p>
                        <ul>
                            <li>Payment methods</li>
                            <li>Cancel your subscription</li>
                            <li>Renew your subscription</li>
                            <li>Update your location</li>
                            <li>Apply promotional codes</li>
                            <li>View subscription invoices</li>
                        </ul>
                        <a href="{{ $user->billingPortalUrl(route('user.billing')) }}" class="btn btn-outline-primary mt-4 w-100">My Billing Portal</a>
                        <p class="mt-3 mb-0 text-center">All billing is handled by <b>Stripe</b></p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-8 offset-md-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4 border-0">
                            <div class="card-body bg-body-tertiary border-start bmx-border-red bmx-border-6 shadow-sm rounded text-center">
                                <p class="m-0">Please select a plan. You can always swap it in the future. All billing is handled by <b>Stripe</b>.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach (config('billing.plans') as $plan)
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h2>{{ $plan['name'] }}</h2>
                                    <p class="mb-0">{{ $plan['pricing'] }} <small>({{ $plan['currency'] }})</small></p>
                                    <hr>
                                    <ul class="unmarked-list my-3">
                                        @foreach ($plan['features'] as $feature)
                                            <li class="bmx-ml-n1"><span class="fa fa-check pe-1 text-success"></span> {{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                    <x-f-action
                                        route="user.billing.subscribe"
                                        method="post"
                                        :payload="['plan' => $plan['key']]"
                                        content="Subscribe"
                                        :options="[
                                            'class' => ($plan['encouraged']) ? 'btn w-100 btn-primary' : 'btn w-100 btn-outline-primary' ,
                                        ]"
                                    />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@stop
