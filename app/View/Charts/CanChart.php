<?php

namespace App\View\Charts;

use Grafite\Charts\Builder\GeoChart;
use Illuminate\Support\Facades\Http;

class CanChart extends GeoChart
{
    public $height = 750;

    public $showGraticule = false;

    public $id = 'mappy';

    public $borderWidth = 0;

    // public $projection = 'albersUsa';
    // public $projection = 'mercator';
    // public $projection = 'naturalEarth1';
    // public $projection = 'equalEarth';
    // public $projection = 'equirectangular';
    // public $projection = 'conicConformal';

    // public $projection = 'albers';
    // public $projection = 'conicEqualArea';
    // public $projection = 'conicConformal';
    // public $projection = 'conicEquidistant';
    public $projection = 'geoMercator';
    // public $projection = 'transverseMercator';
    // public $projection = 'stereographic';
    // public $projection = 'orthographic';
    // public $projection = 'gnomonic';
    // public $projection = 'azimuthalEquidistant';
    // public $projection = 'azimuthalEqualArea';

    public $displayAxes = false;

    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function collectData()
    {
        $data = Http::get('https://gist.githubusercontent.com/Brideau/2391df60938462571ca9/raw/f5a1f3b47ff671eaf2fb7e7b798bacfc6962606a/canadaprov.json')->json();

        return $data['features'];

        return $this->parseGeoData($data);
    }

    public function labels()
    {
        return collect($this->data)->map(function ($country) {
            return $country['properties']['name'];
        });

    }

    public function datasets()
    {
        $data = collect($this->data)->map(function ($country) {
            return [
                'feature' => $country,
                'value' => rand(1, 100),
            ];
        });

        $dataset = $this->makeDataset('Words', $data)
            ->options([
                'outline' => $this->data,
                'showOutline' => true,
            ]);

        return [
            $dataset,
        ];
    }
}
