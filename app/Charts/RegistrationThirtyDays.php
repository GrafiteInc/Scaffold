<?php

namespace App\Charts;

use App\Models\User;
use Grafite\Charts\Builder\Chart;

class RegistrationThirtyDays extends Chart
{
    public $height = 250;

    public $title = 'User Activities';

    public $displayTitle = false;

    public $borderWidth = 4;

    public $activityRecords;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        $registrations = User::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('d-m-Y');
            });

        $this->registrationRecords = collect([]);

        foreach ($registrations as $date => $registration) {
            $this->registrationRecords->push([
                'dates' => $date,
                'registration_count' => $registration->count(),
            ]);
        }
    }

    public function labels()
    {
        return $this->registrationRecords->pluck('dates');
    }

    public function datasets()
    {
        $counts = $this->registrationRecords->pluck('registration_count');

        $dataset = $this->makeDataset('User Registrations Per Day', $counts)
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        return [
            $dataset
        ];
    }
}
