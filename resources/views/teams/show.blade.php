@extends('layouts.dashboard')

@section('page-title') Teams: {{ $team->name }} @stop

@section('content')

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="btn-toolbar justify-content-beginning">
                <a class="btn btn-outline-secondary" href="{{ route('teams') }}">
                    <span class="fas fa-arrow-left"></span> Back to Teams
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4 d-flex justify-content-left">
            <div class="avatar shadow-sm border" style="background-image: url({{ $team->avatar_url }})"></div>
        </div>
        <div class="col-md-4">
            <h3>{{ $team->name }}</h3>
            <p>Created On: {{ $team->created_at->format('M jS, Y') }}</p>
        </div>
        <div class="col-md-4">
            @if (Gate::allows('team-manager', $team))
                {!! $inviteForm !!}
            @endif
        </div>
    </div>

    <div class="row mt-6">
        <div class="col-md-12">
            @if ($team->members->isEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                No members found.
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Team Members</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($team->members as $member)
                                    <tr>
                                        <td>{{ $member->name }} ({{ $member->email }})</td>
                                        @if (Gate::allows('team-manager', $team))
                                            <td width="200px">
                                                <div class="btn-toolbar justify-content-end">
                                                    <a href="{{ route('teams.members.edit', [$team, $member]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        @include('teams.invites')
    </div>
@stop
