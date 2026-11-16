<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\DatetimeLocal;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\ModalForm;

class DisableUserForm extends ModalForm
{
    /**
     * The form route. Set per user with `setRoute('admin.users.disable', [$user->id])`.
     *
     * @var string
     */
    public $route = 'admin.users.disable';

    /**
     * Buttons and values
     *
     * @var array
     */
    public $buttons = [
        'submit' => 'Disable User',
    ];

    /**
     * Button classes.
     *
     * @var array
     */
    public $buttonClasses = [
        'submit' => 'btn btn-danger',
    ];

    /**
     * Trigger content (usually a button)
     *
     * @var string
     */
    public $triggerContent = '<span class="fas fa-fw fa-ban"></span> Disable User';

    /**
     * Trigger class (usually for a button)
     *
     * @var string
     */
    public $triggerClass = 'btn btn-outline-danger ms-2';

    /**
     * Set the desired fields for the form
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('reason', [
                'placeholder' => 'Shown to admins only',
            ]),
            DatetimeLocal::make('until', [
                'label' => 'Disabled until (leave blank to disable permanently)',
            ]),
        ];
    }
}
