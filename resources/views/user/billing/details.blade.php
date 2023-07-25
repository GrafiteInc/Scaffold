@extends('layouts.app')

@section('page-title', 'Billing')

@section('content')

    @include('user.billing.tabs')

    <div class="row">
        <div class="col-md-6 mt-4">
            @include('user.billing.information', ['disabled' => true])
            @include('user.billing.current-plan')
        </div>
        <div class="col-md-6 mt-4">
            <div class="row">
                @if ($subscription)
                    @if (! $subscription->cancelled())
                        <div class="col-md-12">
                            @include('user.billing.upcoming-invoice')
                        </div>
                        <div class="col-md-12 mt-4 text-end">
                            {!! form()->confirm('Are you sure you want to cancel your subscription?', 'app.confirmation')
                                ->action('post', 'user.subscription.cancel',
                                    'Cancel Subscription',
                                    ['class' => 'btn btn-outline-danger']
                            ) !!}
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Cancelled Subscription
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0">
                                        <table class="table">
                                            <tr>
                                                <th>Ends At</th>
                                                <th class="text-end">{{ $subscription->ends_at->format('F j, Y') }}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

@stop
