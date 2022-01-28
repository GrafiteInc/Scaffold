@extends('layouts.admin')

@section('page-title', 'Admin: Invite User')

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <x-forms.invite-user></x-forms.invite-user>
        </div>
    </div>

@stop