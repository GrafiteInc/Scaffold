<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;
use App\View\Forms\TeamInviteForm;

class TeamInvite extends Component
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
        return app(TeamInviteForm::class)
            ->setRoute('teams.members.invite', $this->team->id)
            ->make()
            ->render();
    }
}
