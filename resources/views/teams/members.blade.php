@extends('layouts.app')

@section('page-title', "{$team->name} Members")

@section('content')

    @include('teams.nav')

    <div class="row">
        <div class="col-md-4 d-flex justify-content-center">
            <div class="avatar shadow-sm border" style="background-image: url({{ $team->avatar_url }})"></div>
        </div>
        <div class="col-md-8 mb-4">
            <x-forms.team-invite :team="$team"></x-forms.team-invite>
        </div>

        <div class="col-md-12 bmx-mt-6">
            @if ($team->members->isEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                No team members.
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Members</h4>
                    </div>
                    <div class="card-body">
                        <table class="table p-0 m-0 table-borderless">
                            <tbody>
                                @foreach($team->members as $member)
                                    <tr>
                                        <td>{{ $member->name }} ({{ $member->email }})</td>
                                        <td>{{ ucfirst($member->membership->team_role) }}</td>
                                        <td width="250px" class="text-end">
                                            @if (Gate::allows('team-admin', $team))
                                                <a href="{{ route('teams.members.edit', [$team->id, $member->id]) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                            @endif

                                            @if (Gate::allows('team-admin', $team))
                                                {!! form()->confirm('Are you sure you want to remove this member?', 'confirmation')
                                                    ->action('delete',
                                                    ['teams.members.remove', $team->id, $member->id],
                                                    'Remove',
                                                    ['class' => 'btn btn-sm btn-outline-danger']
                                                ) !!}
                                            @endif
                                        </td>
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
