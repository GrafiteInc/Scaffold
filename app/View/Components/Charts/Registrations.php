<?php

namespace App\View\Components\Charts;

use App\View\Charts\RegistrationThirtyDays;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Registrations extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return app(RegistrationThirtyDays::class)->html();
    }
}
