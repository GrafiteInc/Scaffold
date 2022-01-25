@extends('layouts.admin')

@section('page-title', 'Admin: Create Role')

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <x-forms.role></x-forms.role>
        </div>
    </div>

@stop
