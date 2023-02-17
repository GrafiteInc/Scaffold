@extends('layouts.admin')

@section('page-title', 'Admin: Invite User')

@section('admin_content')
    <div class="row">
        <div class="col-md-12">
            <x-f-base :form="\App\View\Forms\InviteUserForm::class" />
        </div>
    </div>
@stop