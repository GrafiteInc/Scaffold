@extends('errors::branded-layout')

@section('code', '429')
@section('title', __('Too Many Requests'))

@section('message', __('Sorry, you are making too many requests to our servers.'))
