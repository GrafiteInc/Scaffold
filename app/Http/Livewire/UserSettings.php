<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Forms\UserForm;
use Illuminate\View\View;

class UserSettings extends Component
{
    public $data;

    public function mount()
    {
        // convert the model to an array
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
