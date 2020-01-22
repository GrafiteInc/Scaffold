<?php

namespace App\Charts;

use App\Models\User;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class RegistrationThirtyDays extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $registrations = User::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('d-m-Y');
            });

        $registrationRecords = collect([]);

        foreach ($registrations as $date => $registration) {
            $registrationRecords->push([
                'dates' => $date,
                'registration_count' => $registration->count()
            ]);
        }

        $labels = $registrationRecords->pluck('dates');
        $counts = $registrationRecords->pluck('registration_count');

        $this->dataset('User Registrations Per Day', 'line', $counts)
        ->options([
            'borderColor' => '#6f42c1',
        ]);

        $this->displayLegend(false);

        $this->labels($labels);

        $this->height(250);
    }
}
