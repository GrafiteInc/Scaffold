<?php

namespace App\View\Components\Forms;

use App\View\Forms\UserPasswordForm;
use Illuminate\View\Component;

class UserPassword extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(UserPasswordForm::class)->make()->render();
    }
}
