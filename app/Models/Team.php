<?php

namespace App\Models;

use App\Models\Concerns\HasAvatar;
use App\Models\Concerns\HasSubscribedUser;
use App\Models\Concerns\Invitable;
use App\View\Forms\TeamForm;
use Grafite\Forms\Traits\HasForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Team extends Model
{
    use HasAvatar;
    use HasFactory;
    use HasForm;
    use HasSubscribedUser;
    use Invitable;

    public $form = TeamForm::class;

    public $relationship = 'memberships';

    public $fillable = [
        'user_id',
        'name',
        'avatar',
        'uuid',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    /**
     * The creator of the team is the true admin.
     *
     * @return int
     */
    public function getAdminAttribute()
    {
        return $this->user_id;
    }

    /**
     * Team members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Team members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->as('membership')
            ->withPivot('team_role');
    }

    /**
     * Get the route to the team, pending on the member type
     *
     * @return void
     */
    public function route()
    {
        if (Gate::allows('team-admin', $this)) {
            return route('teams.members', $this->id);
        }

        return route('teams.show', $this->uuid);
    }
}
