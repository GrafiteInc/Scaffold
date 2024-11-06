<?php

namespace App\View\Charts;

use Grafite\Charts\Builder\GeoChart;
use Illuminate\Support\Facades\Http;

class UsChart extends GeoChart
{
    public $height = 750;

    public $id = 'mappy';

    public $borderWidth = 0;

    public $projection = 'albersUsa';

    public $displayAxes = false;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function collectData()
    {
        $data = json_encode(Http::get('https://unpkg.com/us-atlas/states-10m.json')->json());

        return $this->parseGeoData($data);
    }

    public function labels()
    {
        return collect($this->data['states']->features)->map(function ($country) {
            return $country->properties->name;
        });

    }

    public function datasets()
    {
        $data = collect($this->data['states']->features)->map(function ($country) {
            return [
                'feature' => $country,
                'value' => rand(1, 100),
            ];
        });

        $nation = $this->data['nation']->features[0];

        $dataset = $this->makeDataset('Words', $data)
            ->options([
                'outline' => $nation,
                'showOutline' => true,
            ]);

        return [
            $dataset,
        ];
    }
}
