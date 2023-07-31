<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;

        return view('user.notifications.index')->with(compact('notifications'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function read(Request $request, $notification)
    {
        $request->user()->notifications->find($notification)->markAsRead();

        return redirect()->back()->withMessage('Marked as read');
    }

    /**
     * Delete a notfication.
     *
     * @param  string  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $notification)
    {
        $request->user()->notifications->find($notification)->delete();

        return redirect()->back()->withMessage('Deleted notification');
    }

    /**
     * Delete all notfications for a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return redirect()->back()->withMessage('Deleted all notifications');
    }
}
