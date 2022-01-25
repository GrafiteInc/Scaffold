<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;
use App\View\Forms\UserPasswordForm;

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
