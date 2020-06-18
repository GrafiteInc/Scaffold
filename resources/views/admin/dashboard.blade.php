@extends('layouts.admin')

@section('page-title') Admin: Dashboard @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12 ">
            @if (session('original_user'))
                <a class="btn btn-secondary pull-right mt-4" href="/users/switch-back">Return to your Login</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    User Registrations (30 Days)
                </div>
                <div class="card-body">
                    {!! $registrationChart->html() !!}
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
                    {!! $activityChart->html() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    {!! $registrationChart->cdn() !!}
    {!! $registrationChart->script() !!}
    {!! $activityChart->script() !!}
@stop
