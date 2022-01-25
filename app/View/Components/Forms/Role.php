<?php

namespace App\View\Components\Forms;

use App\View\Forms\RoleForm;
use Illuminate\View\Component;

class Role extends Component
{
    public $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->role) {
            return app(RoleForm::class)->disabledWhen(function () {
                return $this->role->name === 'admin';
            })->edit($this->role)->render();
        }

        return app(RoleForm::class)->create()->render();
    }
}
