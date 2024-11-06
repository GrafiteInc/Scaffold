<?php

namespace App\View\Charts;

use Grafite\Charts\Builder\WordCloud;

class WordyChart extends WordCloud
{
    public $height = 250;

    public $displayAxes = false;

    public $tooltipAlwaysOn = false;

    public $activityRecords;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function collectData()
    {
        return collect([
            'hello' => 99,
            'world' => 9,
            'panzer' => 67,
            'britain' => 17,
        ]);
    }

    public function labels()
    {
        return $this->data->keys();
    }

    public function datasets()
    {
        $dataset = $this->makeDataset('Words', $this->data->values())
            ->options([
                'borderColor' => '#6f42c1',
            ]);

        return [
            $dataset,
        ];
    }
}
