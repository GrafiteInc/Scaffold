<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;
use App\View\Forms\InviteUserForm;

class InviteUser extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(InviteUserForm::class)->make()->render();
    }
}
