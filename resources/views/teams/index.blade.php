@extends('layouts.dashboard')

@section('page-title') Teams @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ route('teams.create') }}">Create Team</a>
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
                                        <td width="20%">
                                            <a href="{!! route('teams.show', $team->uuid) !!}">{{ $team->name }}</a>
                                        </td>
                                        <td width="30%" class="d-none d-md-block">
                                            @foreach ($team->members->take(5) as $member)
                                                <div class="avatar-sm shadow-sm border" style="background-image: url({{ $member->avatar_url }})"></div>
                                            @endforeach
                                        </td>
                                        <td width="50%" class="text-right">
                                            <div class="btn-toolbar justify-content-end">
                                                <a class="btn btn-outline-primary btn-sm mr-2" href="{!! route('teams.edit', $team) !!}"><i class="fa fa-edit"></i> Edit</a>
                                                {!! app(\App\Http\Forms\TeamForm::class)->confirm('Are you sure you want to delete '.$team->name.'?', 'confirmation')->delete($team) !!}
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
                                            <div class="btn-toolbar justify-content-end">
                                                <a class="btn btn-outline-primary btn-sm mr-2" href="{!! route('teams.show', $team->uuid) !!}"><i class="fa fa-eye"></i> View</a>
                                                {!! form()->confirm('Are you sure you want to leave '.$team->name.'?', 'confirmation')->action('post',
                                                    ['teams.leave', $team],
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