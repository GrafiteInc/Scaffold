@extends('errors.branded-layout')

@section('code', '503')

@if (app()->isDownForMaintenance())
    @section('title', __('Currently Under Maintanance'))
    @section('message', __('Sorry, we are doing some maintenance. Please check back soon.'))
@else
    @section('title', __('Service Unavailable'))
    @section('message', __($exception->getMessage()))
@endif
