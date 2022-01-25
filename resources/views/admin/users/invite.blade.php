@extends('layouts.admin')

@section('page-title', 'Admin: Invite User')

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <x-forms.user-invite></x-forms.user-invite>
        </div>
    </div>

@stop