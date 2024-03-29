<?php

namespace App\Livewire;

use App\View\Forms\CartForm;
use Livewire\Component;

class Cart extends Component
{
    public $data;

    protected $rules = [
        'data.count' => 'lte:1000',
    ];

    public function mount()
    {
        $this->data['count'] = 88;
    }

    public function setNumber()
    {
        $this->validate();

        $this->data['count'] = 987987897;
    }

    public function render()
    {
        return app(CartForm::class)
            ->setErrorBag($this->getErrorBag())
            ->make($this->data)
            ->renderForLivewire();
    }
}
