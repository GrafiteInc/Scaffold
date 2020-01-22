<?php

namespace App\Models;

use App\Models\User;
use App\Models\Concerns\Invitable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use Invitable;

    public $table = "teams";

    public $relationship = "teamMemberships";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'name',
    ];

    public static $rules = [
        'name' => 'required|unique:teams'
    ];

    /**
     * The creator of the team is the true admin
     *
     * @return int
     */
    public function getAdminAttribute()
    {
        return $this->user_id;
    }

    /**
     * Team members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Team members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
}
