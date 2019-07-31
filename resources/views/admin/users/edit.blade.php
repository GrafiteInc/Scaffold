@extends('layouts.admin')

@section('page-title') Admin: Edit User @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12 text-right">
            @if (! session('original_user'))
                {!! form()->action('post', ['admin.users.switch', $user->id], 'Login as this User', [ 'class' => 'btn btn-secondary' ]) !!}
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            {!! $form !!}
        </div>
    </div>

@stop