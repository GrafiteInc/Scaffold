@extends('layouts.user')

@section('page-title', 'Notifications')

@section('user_content')

    @if ($notifications->isEmpty())
        <div class="card mt-3 text-center shadow-sm">
            <div class="card-body">
                No notifications, that's like inbox ZERO.
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="m-0">App Notifications</h4>
                    </div>
                    <div class="col-md-6">
                        {!! form()->setModal('Delete All', 'btn btn-sm btn-outline-danger float-end', '<p>Are you sure you want to delete all notifications?</p>')
                            ->action('delete', 'user.notifications.clear', 'Confirm', ['class' => 'btn  btn-outline-primary float-end'], true) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless m-0 p-0">
                    <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td width="140px" class="d-none d-md-table-cell">{{ $notification->created_at->format('d M, Y') }}</td>
                            <td @if ($notification->data['is_important']) class="text-warning" @endif>{{ $notification->data['message'] }}</td>
                            <td width="250px">
                                <div class="btn-toolbar justify-content-end">
                                    @if (is_null($notification->read_at))
                                        {!! form()->action('post',
                                            ['user.notifications.read', $notification->id],
                                            'Mark as Read',
                                            ['class' => 'btn btn-sm btn-outline-primary me-2'],
                                            false, true
                                        ) !!}
                                    @endif

                                    <x-f-modal
                                        message='<p class="mb-4">Are you sure you want to delete this?</p>'
                                        content="Confirm"
                                        method="delete"
                                        :route="['user.notifications.destroy', $notification->id]"
                                        triggerContent="<span class='fas fa-fw fa-trash'></span> Delete"
                                        triggerClass="btn btn-sm btn-outline-danger"
                                        :options="['class' => 'btn btn-outline-primary float-end']"
                                        :disableOnSubmit=true
                                    ></x-f-modal>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@stop
