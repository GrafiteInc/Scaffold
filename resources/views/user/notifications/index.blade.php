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
        <div class="card card-body shadow-sm bg-body-tertiary">
            <div class="btn-toolbar justify-content-end">
                {!! form()->setModal('Delete All', 'btn btn-danger d-inline-block float-end', '<p>Are you sure you want to delete all notifications?</p>')
                    ->action('delete', 'user.notifications.clear', 'Confirm', ['class' => 'btn btn-outline-primary float-end'], true) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-carded m-0 p-0">
                    <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td @class([
                               'text-warning' => $notification->data['is_important'] && ! $notification->read_at,
                               'text-success' => ($notification->data['is_important'] && $notification->read_at) || (! $notification->data['is_important'] && $notification->read_at),
                            ])>
                                <x-html-offcanvas
                                    :title="Str::of($notification->data['message'])->limit(30)"
                                    cssClass="btn btn-sm btn-transparent text-truncate text-start btn-text me-2"
                                    position="end"
                                >
                                    {{ $notification->data['message'] }}
                                </x-html-offcanvas>
                            </td>
                            <td width="125px">
                                <div class="btn-toolbar justify-content-end">
                                    <x-f-modal
                                        message='<p class="mb-4">Are you sure you want to delete this?</p>'
                                        content="Confirm"
                                        method="delete"
                                        :route="['user.notifications.destroy', $notification->id]"
                                        triggerContent="<span class='fas fa-fw fa-trash'></span>"
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
