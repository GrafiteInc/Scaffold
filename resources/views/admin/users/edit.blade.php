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
            <h4>Activities</h4>
        </div>
        <div class="col-md-12 mt-2">
            <table class="table table-striped">
                <tr>
                    <th>Description</th>
                    <th width="130px" class="text-right">Created At</th>
                </tr>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->description }} ({{ $activity->request['url'] }})</td>
                        <td class="text-right">{{ $activity->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@stop