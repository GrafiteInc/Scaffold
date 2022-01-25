<?php

namespace App\View\Components\Forms;

use App\View\Forms\LogoutForm;
use Illuminate\View\Component;

class Logout extends Component
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
        return app(LogoutForm::class)->make()->render();
    }
}
