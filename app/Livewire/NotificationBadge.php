<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBadge extends Component
{
    public $count;

    public function getListeners()
    {
        return [
            'refresh' => 'refresh',
        ];
    }

    public function refresh()
    {
        $this->countNotifications();
    }

    public function render()
    {
        $this->count = 0;

        $this->countNotifications();

        if ($this->count > 0) {
            return '<span class="badge badge-pill bg-primary notification-badge rounded-circle">'.$this->count.'</span>';
        }

        return '<span></span>';
    }

    public function countNotifications()
    {
        $this->count = auth()->user()->unreadNotifications->count();
    }
}
