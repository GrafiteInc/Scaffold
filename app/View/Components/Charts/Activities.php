<?php

namespace App\View\Components\Charts;

use Illuminate\View\Component;
use App\View\Charts\ActivityThirtyDays;

class Activities extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(ActivityThirtyDays::class)->html();
    }
}
