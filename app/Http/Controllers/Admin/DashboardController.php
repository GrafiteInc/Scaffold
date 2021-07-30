<?php

namespace App\Http\Controllers\Admin;

use App\Charts\ActivityThirtyDays;
use App\Http\Controllers\Controller;
use App\Charts\RegistrationThirtyDays;

class DashboardController extends Controller
{
    /**
     * The admin Dashboard
     */
    public function __invoke()
    {
        $registrationChart = new RegistrationThirtyDays();
        $activityChart = new ActivityThirtyDays();

        return view('admin.dashboard')
            ->with(compact('activityChart', 'registrationChart'));
    }
}
