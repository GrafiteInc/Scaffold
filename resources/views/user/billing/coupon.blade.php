@extends('layouts.dashboard')

@section('page-title') Billing: Coupon @stop

@section('content')

    @include('user.billing.tabs')

    <div class="row">
        <div class="col-md-6 mt-4">
            @include('user.billing.information', ['disabled' => true])
            @include('user.billing.current-plan')
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('user.billing.apply-coupon') }}">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                Coupon Code
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <input class="form-control" type="text" name="coupon" id="coupon" required placeholder="Code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-right mt-4">
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit">Apply Coupon</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
