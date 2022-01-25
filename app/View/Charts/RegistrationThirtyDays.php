<?php

namespace App\View\Charts;

use App\Models\User;
use Grafite\Charts\Builder\Chart;

class RegistrationThirtyDays extends Chart
{
    public $height = 250;
    public $displayAxes = false;
    public $tooltipAlwaysOn = true;

    public function collectData()
    {
        $registrations = User::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('d-m-Y');
            });

        $data = collect();

        foreach ($registrations as $date => $registration) {
            $data->push([
                'dates' => $date,
                'registration_count' => $registration->count(),
            ]);
        }

        return $data;
    }

    public function labels()
    {
        return $this->data->pluck('dates');
    }

    public function datasets()
    {
        $counts = $this->data->pluck('registration_count');

        $dataset = $this->makeDataset('User Registrations Per Day', $counts)
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        return [
            $dataset,
        ];
    }
}
