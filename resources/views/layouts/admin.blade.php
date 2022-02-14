@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('admin.nav')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @yield('admin_content')
        </div>
    </div>
@stop
