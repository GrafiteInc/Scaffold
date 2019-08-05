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
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">My Teams</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($teams as $team)
                                    <tr>
                                        <td>{{ $team->name }}</td>
                                        <td width="140px" class="text-right">
                                            <div class="btn-toolbar justify-content-between">
                                                <a class="btn btn-outline-primary btn-sm" href="{!! route('user.teams.edit', $team) !!}"><i class="fa fa-edit"></i> Edit</a>
                                                {!! app(\App\Http\Forms\TeamForm::class)->confirm('Are you sure you want to delete '.$team->name.'?')->delete($team) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0">Teams I've Joined</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($memberships as $team)
                                    <tr>
                                        <td>{{ $team->name }}</td>
                                        <td width="180px" class="text-right">
                                            <div class="btn-toolbar justify-content-between">
                                                <a class="btn btn-outline-primary btn-sm" href="{!! route('user.teams.show', $team) !!}"><i class="fa fa-eye"></i> View</a>
                                                {!! form()->confirm('Are you sure you want to leave '.$team->name.'?')->action('post',
                                                    ['user.teams.leave', $team],
                                                    'Leave Team',
                                                    ['class' => 'btn btn-sm btn-outline-warning']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop