@extends('layouts.admin')

@section('page-title', 'Admin: Create Announcement')

@section('admin_content')
    <div class="row">
        <div class="col-md-12">
            <x-f-base :form="\App\View\Forms\AnnouncementForm::class" />
        </div>
    </div>
@stop
