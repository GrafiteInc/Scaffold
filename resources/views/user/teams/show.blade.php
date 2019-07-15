@extends('layouts.user')

@section('page-title') Teams: View @stop

@section('user_content')

    <div class="row mt-3">
        <div class="col-md-12">
            <h3>{{ $team->name }}</h3>
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
                <table class="table table-striped">
                    <thead>
                        <th>Name</th>
                    </thead>
                    <tbody>
                        @foreach($team->members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop
