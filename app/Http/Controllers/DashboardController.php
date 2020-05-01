<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Display the Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        return view('dashboard.main');
    }
}
