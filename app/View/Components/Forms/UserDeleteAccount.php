<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class UserDeleteAccount extends Component
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
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return form()
            ->action('delete', 'user.destroy', 'Please delete my account', [
                'class' => 'btn btn-outline-primary float-end',
            ])
            ->confirmAsModal(trans('general.user.delete_account'), 'Delete My Account', 'btn d-block w-100 btn-danger bmx-mb-6');
    }
}
