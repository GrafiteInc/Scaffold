@extends('layouts.user')

@section('page-title') Teams @stop

@section('user_content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ route('user.teams.create') }}">Create Team</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            @if ($teams->isEmpty())
                <div class="card card-default text-center shadow-sm">
                    <div class="card-body">
                        <span>You have not made any teams yet.</span>
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                        <th width="145px" class="text-right">Action</th>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>
                                    <div class="btn-toolbar justify-content-between">
                                        <a class="btn btn-outline-primary btn-sm" href="{!! route('user.teams.edit', $team) !!}"><i class="fa fa-edit"></i> Edit</a>
                                        {!! app(\App\Http\Forms\TeamForm::class)->confirm('Are you sure you want to delete this Team?')->delete($team) !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            @if ($memberships->isEmpty())
                <div class="card card-default text-center shadow-sm">
                    <div class="card-body">
                        <span>You have not joined any teams yet.</span>
                    </div>
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                        <th width="180px" class="text-right">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($memberships as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>
                                    <div class="btn-toolbar justify-content-between">
                                        <a class="btn btn-outline-primary btn-sm" href="{!! route('user.teams.show', $team) !!}"><i class="fa fa-eye"></i> View</a>
                                        {!! form()->confirm('Are you sure you want to leave this Team?')->action('post',
                                            ['user.teams.leave', $team],
                                            'Leave Team',
                                            ['class' => 'btn btn-sm btn-outline-warning']) !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@stop