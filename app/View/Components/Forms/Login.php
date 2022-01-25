<?php

namespace App\View\Components\Forms;

use App\View\Forms\LoginForm;
use Illuminate\View\Component;

class Login extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(LoginForm::class)->make()->render();
    }
}
