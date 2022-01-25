<?php

namespace App\View\Components\Forms;

use App\View\Forms\TeamForm;
use Illuminate\View\Component;

class Team extends Component
{
    public $team;

    public function __construct($team = null)
    {
        $this->team = $team;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->team) {
            return app(TeamForm::class)->edit($this->team)->render();
        }

        return app(TeamForm::class)->create()->render();
    }
}
