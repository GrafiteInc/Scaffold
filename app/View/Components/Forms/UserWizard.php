<?php

namespace App\View\Components\Forms;

use App\View\Forms\UserWizardForm;
use Illuminate\View\Component;

class UserWizard extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(UserWizardForm::class)->make()->render();
    }
}
