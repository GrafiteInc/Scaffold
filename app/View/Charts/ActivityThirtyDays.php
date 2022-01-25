<?php

namespace App\View\Charts;

use App\Models\Activity;
use Grafite\Charts\Builder\Chart;

class ActivityThirtyDays extends Chart
{
    public $height = 250;
    public $id = 'Activities';
    public $displayAxes = false;
    public $tooltipAlwaysOn = true;
    public $activityRecords;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function collectData()
    {
        $activities = Activity::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('d-m-Y');
            });

        $activityRecords = collect();

        foreach ($activities as $date => $activity) {
            $activityRecords->push([
                'dates' => $date,
                'activity_count' => $activity->count(),
            ]);
        }

        return $activityRecords;
    }

    public function labels()
    {
        return $this->data->pluck('dates');
    }

    public function datasets()
    {
        $counts = $this->data->pluck('activity_count');

        $dataset = $this->makeDataset('Activity Count', $counts)
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        return [
            $dataset,
        ];
    }
}
