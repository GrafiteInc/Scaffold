<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return view('user.notifications.index')
            ->with('notifications', $notifications);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function read($notification)
    {
        auth()->user()->notifications->find($notification)->markAsRead();

        return back()->with('message', 'Marked as read');
    }

    /**
     * Delete a notfication
     *
     * @param  string $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($notification)
    {
        auth()->user()->notifications->find($notification)->delete();

        return back()->with('message', 'Deleted notification');
    }

    /**
     * Delete all notfications for a user
     *
     * @param  string $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('message', 'Deleted all notifications');
    }
}
