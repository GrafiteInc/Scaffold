<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TeamNavbar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return Navbar::make([
            NavLink::make()->when(),
            NavButton::make()->when(),
        ]);
    }
}
