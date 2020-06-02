<?php

namespace App\Http\Forms;

use App\Models\Role;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Forms\BaseForm;
use Grafite\FormMaker\Fields\Bootstrap\HasMany;

class InviteUserForm extends BaseForm
{
    /**
     * The form route
     *
     * If you need to inject a parameter
     * to the route, then use the `setRoute`
     * method.
     *
     * @var string
     */
    public $route = 'admin.users.send-invite';

    public $orientation = 'horizontal';

    /**
     * Buttons and values
     *
     * @var array
     */
    public $buttons = [
        'submit' => 'Send',
    ];

    /**
     * Set the desired fields for the form
     *
     * @return array
     */
    public function fields()
    {
        return [
            Email::make('email', [
                'required' => true,
            ]),
            HasMany::make('roles', [
                'model' => Role::class,
                'required' => true,
                'model_options' => [
                    'label' => 'label',
                ],
            ]),
        ];
    }
}
