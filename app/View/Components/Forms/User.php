<?php

namespace App\View\Components\Forms;

use App\View\Forms\UserForm;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class User extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return app(UserForm::class)->edit(auth()->user())->render();
    }
}
