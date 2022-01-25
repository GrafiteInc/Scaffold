@extends('layouts.admin')

@section('page-title', 'Admin: Edit User')

@section('admin_content')

    <div class="row">
        <div class="col-md-12 text-end">
            @if (! session('original_user'))
                {!! form()->action('post', ['admin.users.switch', $user->id], 'Login as this User', [ 'class' => 'btn btn-outline-secondary' ]) !!}
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <x-forms.admin-user :user="$user"></x-forms.admin-user>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            @if ($activities->isEmpty())
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        No known activities
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Activities <small>(Last 25)</small></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-hover p-0 m-0">
                            @foreach($activities as $activity)
                                <tr onclick="window.modal('Activity', '{{ $activity->forModal() }}')">
                                    <td>{{ $activity->description }}</td>
                                    <td width="250px" class="d-none d-sm-table-cell">
                                        <b>{{ $activity->request['method'] }}:</b> {{ Str::limit(str_replace(url('/'), '', $activity->request['url']), 20) }}
                                    </td>
                                    <td width="180px" class="text-end">{{ $activity->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop