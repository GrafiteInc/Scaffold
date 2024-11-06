<?php

namespace App\View\Charts;

use Grafite\Charts\Builder\GeoChart;
use Illuminate\Support\Facades\Http;

class GlobalChart extends GeoChart
{
    public $height = 750;

    public $id = 'mappy';

    public $borderWidth = 0;

    public $displayAxes = false;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function collectData()
    {
        $data = json_encode(Http::get('https://unpkg.com/world-atlas/countries-50m.json')->json());

        return $this->parseGeoData($data);
    }

    public function labels()
    {
        return collect($this->data['countries']->features)->map(function ($country) {
            return $country->properties->name;
        });

    }

    public function datasets()
    {
        $data = collect($this->data['countries']->features)->map(function ($country) {
            return [
                'feature' => $country,
                'value' => rand(1, 100),
            ];
        });

        $dataset = $this->makeDataset('Words', $data)
            ->options([
                'borderWidth' => 0,
            ]);

        return [
            $dataset,
        ];
    }
}
