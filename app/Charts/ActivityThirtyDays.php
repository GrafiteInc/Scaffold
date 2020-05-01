<?php

namespace App\Charts;

use App\Models\Activity;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class ActivityThirtyDays extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $activities = Activity::where('created_at', '<', now())
            ->where('created_at', '>', now()->subDays(30))
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('d-m-Y');
            });

        $activityRecords = collect([]);

        foreach ($activities as $date => $activity) {
            $activityRecords->push([
                'dates' => $date,
                'activity_count' => $activity->count(),
            ]);
        }

        $labels = $activityRecords->pluck('dates');
        $counts = $activityRecords->pluck('activity_count');

        $this->dataset('User Activities Per Day', 'line', $counts)
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        $this->displayLegend(false);

        $this->labels($labels);

        $this->height(250);
    }
}
