<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Show the form for inviting a customer.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Show the form for creating a Role.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        User::get()->each(function ($user) use ($request) {
            app_notify($request->message, true, $user);
        });

        return redirect()->route('admin.announcements.create')->with('message', 'Announcements Sent');
    }
}
