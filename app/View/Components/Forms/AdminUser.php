<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;
use App\View\Forms\AdminUserForm;

class AdminUser extends Component
{
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(AdminUserForm::class)->edit($this->user)->render();
    }
}
