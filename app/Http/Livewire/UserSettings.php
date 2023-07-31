<?php

namespace App\Http\Livewire;

use App\Http\Forms\UserForm;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * NOT IN USE
 * This is a PoC of how to implement the Grafite Forms with Livewire Components
 * which adds a more real time flow to an application.
 */
class UserSettings extends Component
{
    use WithFileUploads;

    public $user;

    public $data;

    public function mount()
    {
        $this->user = request()->user();

        $this->data['avatar'] = $this->user->avatar;
        $this->data['name'] = $this->user->name;
        $this->data['email'] = $this->user->email;
        $this->data['billing_email'] = $this->user->billing_email;
        $this->data['state'] = $this->user->state;
        $this->data['country'] = $this->user->country;
        $this->data['allow_email_based_notifications'] = (bool) $this->user->allow_email_based_notifications;
        $this->data['two_factor_platform'] = $this->user->two_factor_platform;
    }

    protected $rules = [
        'data.billing_email' => 'required|email',
    ];

    public function submit()
    {
        $this->validate();

        if ($this->data['avatar'] !== $this->user->avatar) {
            Storage::delete($this->user->avatar);
            $path = Storage::putFile('public/avatars', $this->data['avatar'], 'public');
            $this->data['avatar'] = $path;
        }

        $this->user->update($this->data);
    }

    public function render()
    {
        return app(UserForm::class)
            ->setErrorBag($this->getErrorBag())
            ->edit($this->user)
            ->renderForLivewire();

        return view('livewire.user-settings')->withForm($form);
    }
}
