<?php

namespace App\View\Components\Forms;

use App\View\Forms\TeamMemberForm;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TeamMember extends Component
{
    public $member;

    public $team;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($member, $team)
    {
        $this->member = $member;
        $this->team = $team;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return app(TeamMemberForm::class)
            ->setMember($this->member)
            ->setRoute('teams.members.update', [$this->team->id, $this->member->id])
            ->make()
            ->render();
    }
}
