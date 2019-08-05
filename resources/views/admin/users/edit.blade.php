@extends('layouts.admin')

@section('page-title') Admin: Edit User @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12 text-right">
            @if (! session('original_user'))
                {!! form()->action('post', ['admin.users.switch', $user->id], 'Login as this User', [ 'class' => 'btn btn-secondary' ]) !!}
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            {!! $form !!}
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
                        <table class="table table-striped table-borderless p-0 m-0">
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->description }}</td>
                                    <td><b>{{ $activity->request['method'] }}:</b> {{ $activity->request['url'] }}</td>
                                    <td class="text-right">{{ $activity->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop