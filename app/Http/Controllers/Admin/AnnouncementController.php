<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    /**
     * Show the form for inviting a customer.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Show the form for creating a Role.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        User::get()->each(function ($user) use ($request) {
            app_notify($request->message, true, $user);
        });

        return redirect()->route('admin.announcements.create')->with('message', 'Announcements Sent');
    }
}
