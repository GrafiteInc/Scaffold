<?php

namespace App\Http\Controllers\Admin;

use App\Charts\ActivityThirtyDays;
use App\Charts\RegistrationThirtyDays;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activityChart = new ActivityThirtyDays();
        $registrationChart = new RegistrationThirtyDays();

        return view('admin.dashboard')
            ->with(compact('activityChart', 'registrationChart'));
    }
}
