<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;
use App\View\Forms\AdminUserForm;

class AdminUserIndexSearch extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(AdminUserForm::class)->index()
            ->search('admin.users.search', 'Search Users', '<span class="fas fa-search"></span>', 'get');
    }
}
