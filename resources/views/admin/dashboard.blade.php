@extends('layouts.admin')

@section('page-title') Admin: Dashboard @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <h3>Dashboard</h3>
            @if (session('original_user'))
                <a class="btn btn-secondary pull-right" href="/users/switch-back">Return to your Login</a>
            @endif
        </div>
    </div>

@stop
