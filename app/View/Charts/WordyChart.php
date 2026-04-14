<?php

namespace App\View\Charts;

use Grafite\Charts\Builder\WordCloud;
use Illuminate\Support\Collection;

class WordyChart extends WordCloud
{
    public $height = 250;

    public $displayAxes = false;

    public $tooltipAlwaysOn = false;

    public $activityRecords;

    /**
     * Initializes the chart.
     *
     * @return Collection
     */
    public function collectData(): Collection
    {
        return collect([
            'hello' => 99,
            'world' => 9,
            'panzer' => 67,
            'britain' => 17,
        ]);
    }

    public function labels(): Collection
    {
        return $this->data->keys();
    }

    public function datasets(): array
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
