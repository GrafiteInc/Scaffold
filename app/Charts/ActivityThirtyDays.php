<?php

namespace App\Charts;

use App\Models\Activity;
use Grafite\Charts\Builder\Chart;

class ActivityThirtyDays extends Chart
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
        $activities = Activity::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('d-m-Y');
            });

        $this->activityRecords = collect([]);

        foreach ($activities as $date => $activity) {
            $this->activityRecords->push([
                'dates' => $date,
                'activity_count' => $activity->count(),
            ]);
        }
    }

    public function labels()
    {
        return $this->activityRecords->pluck('dates');
    }

    public function datasets()
    {
        $counts = $this->activityRecords->pluck('activity_count');

        $dataset = $this->makeDataset('User Activities Per Day', $counts)
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        return [
            $dataset,
        ];
    }
}
