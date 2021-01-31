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
        $activityChart = new ActivityThirtyDays();
        $registrationChart = new RegistrationThirtyDays();

        return view('admin.dashboard')
            ->with(compact('activityChart', 'registrationChart'));
    }
}
