@extends('layouts.admin')

@section('page-title') Admin: Dashboard @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12 mt-3">
            @if (session('original_user'))
                <a class="btn btn-secondary pull-right" href="/users/switch-back">Return to your Login</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header">
                    User Registrations (30 Days)
                </div>
                <div class="card-body">
                    {!! $registrationChart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header">
                    User Activities (30 Days)
                </div>
                <div class="card-body">
                    {!! $activityChart->container() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $registrationChart->script() !!}
    {!! $activityChart->script() !!}
@stop
