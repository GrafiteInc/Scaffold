<?php

namespace App\View\Components\Charts;

use App\View\Charts\ActivityThirtyDays;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Activities extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return app(ActivityThirtyDays::class)->html();
    }
}
