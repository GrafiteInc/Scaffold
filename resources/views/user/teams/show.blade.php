@extends('layouts.user')

@section('page-title') Teams: View @stop

@section('user_content')

    <div class="row mt-3">
        <div class="col-md-12">
            <h3>{{ $team->name }}</h3>
            <p class="lead">Created On: {{ $team->created_at->format('dS M, Y') }}</p>
        </div>
    </div>

    <div class="row mt-3">
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
                        <table class="table table-striped table-borderless m-0 p-0">
                            <tbody>
                                @foreach($team->members as $member)
                                    <tr>
                                        <td>{{ $member->name }}</td>
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
