@extends('layouts.dashboard')

@section('page-title', 'Billing: Change Plan')

@section('content')

    @include('user.billing.tabs')

    <div class="row">
        <div class="col-md-6 mt-4">
            @include('user.billing.information', ['disabled' => true])
            @include('user.billing.current-plan')
        </div>

        <div class="col-md-6 mt-4">
            <form method="POST" action="{{ route('user.billing.swap-plan') }}">
                {!! csrf_field() !!}
                @include('user.billing.plans', ['unlabelled' => true])

                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-primary" onclick="window.pending()" type="submit">Change Plan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
