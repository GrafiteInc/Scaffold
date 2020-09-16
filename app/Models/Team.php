<?php

namespace App\Models;

use App\Models\Concerns\HasAvatar;
use App\Models\Concerns\Invitable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use Invitable;
    use HasAvatar;

    public $relationship = 'teamMemberships';

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
}
