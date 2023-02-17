<?php

namespace App\View\Forms;

use App\Models\Role;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Forms\ComponentForm;
use Grafite\Forms\Fields\Bootstrap\HasOne;

class InviteUserForm extends ComponentForm
{
    /**
     * The form route.
     *
     * If you need to inject a parameter
     * to the route, then use the `setRoute`
     * method.
     *
     * @var string
     */
    public $route = 'admin.users.send-invite';

    public $orientation = 'horizontal';

    public $disableOnSubmit = true;

    /**
     * Buttons and values.
     *
     * @var array
     */
    public $buttons = [
        'submit' => 'Send',
    ];

    /**
     * Set the desired fields for the form.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Email::make('email', [
                'required' => true,
            ]),
            HasOne::make('roles', [
                'model' => Role::class,
                'required' => true,
                'model_options' => [
                    'label' => 'label',
                ],
            ]),
        ];
    }
}
