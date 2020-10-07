<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Forms\UserForm;

/**
 * NOT IN USE
 * This is a PoC of how to implement the Grafite Forms with Livewire Components
 * which adds a more real time flow to an application.
 */

class UserSettings extends Component
{
    public $data;

    public function mount()
    {
        $user = request()->user();

        $this->data['name'] = $user->name;
        $this->data['email'] = $user->email;
        $this->data['billing_email'] = $user->billing_email;
        $this->data['state'] = $user->state;
        $this->data['country'] = $user->country;
    }

    protected $rules = [
        'data.billing_email' => 'required|email',
    ];

    public function submit()
    {
        $this->validate();

        request()->user()->update($this->data);
    }

    public function render()
    {
        $user = request()->user();

        $form = app(UserForm::class)
            ->setErrorBag($this->getErrorBag())
            ->edit($user);

        return view('livewire.user-settings')->withForm($form);
    }
}
