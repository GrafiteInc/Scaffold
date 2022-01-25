<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * The admin Dashboard
     */
    public function __invoke()
    {
        return view('admin.dashboard');
    }
}
