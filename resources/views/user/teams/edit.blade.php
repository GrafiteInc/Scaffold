@extends('layouts.user')

@section('page-title') Teams: Edit @stop

@section('user_content')

    <div class="row">
        <div class="col-md-6 mb-4">
            <div>
                {!! $form !!}
            </div>
        </div>
        <div class="col-md-6 mb-4">
            {!! $inviteForm !!}
        </div>

        <div class="col-md-12">
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
                                        <td width="220px" class="text-right">
                                            @if (Gate::allows('team-admin', $team))
                                                <a href="{{ route('teams.members.edit', [$team->id, $member->id]) }}" class="btn btn-sm btn-outline-primary mr-2">Edit</a>
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
