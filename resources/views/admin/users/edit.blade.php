@extends('layouts.admin')

@section('page-title') Admin: Edit User @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12 justify-content-end">
            @if (! Session::get('original_user'))
                <a class="btn btn-secondary" href="/admin/users/switch/{{ $user->id }}">Login as this User</a>
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            {!! $form !!}
        </div>
    </div>

@stop