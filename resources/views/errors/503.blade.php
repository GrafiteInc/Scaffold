@extends('errors.branded-layout')

@section('code', '503')
@section('title', __('Service Unavailable'))

@section('message', __($exception->getMessage() ?: __('Sorry, we are doing some maintenance. Please check back soon.')))
