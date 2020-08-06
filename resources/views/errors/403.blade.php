@extends('errors.branded-layout')

@section('code', '403')
@section('title', __('Forbidden'))

@section('message', __($exception->getMessage() ?: __('Sorry, you are forbidden from accessing this page.')))
