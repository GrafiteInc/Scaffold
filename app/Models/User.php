<?php

namespace App\Models;

use App\Models\Concerns\HasRoles;
use App\Models\Concerns\HasTeams;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\HasActivity;
use App\Notifications\ResetPassword;
use App\Models\Concerns\HasPermissions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable,
        HasTeams,
        HasActivity,
        HasRoles,
        HasPermissions,
        Searchable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Appends
     *
     * @var array
     */
    public $appends = [
        'avatar',
    ];

    /**
     * User Invites
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites()
    {
        return $this->hasMany(Invite::class, 'email', 'email')
            ->whereNotNull('model_id');
    }

    /**
     * Gravatar Image
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        // Create an image avatar
        // Also allow profile pic uploads

        // $hash = md5(strtolower(trim($this->email)));

        // return "https://www.gravatar.com/avatar/{$hash}&s=20";
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function notifyPasswordReset($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Prepare a payload for the JS session data
     *
     * @return array
     */
    public function jsonSessionData()
    {
        $visibleAttributes = [
            'id',
            'name',
            'email'
        ];

        return json_encode(collect($this->toArray())
            ->filter(function ($value, $attribute) use ($visibleAttributes) {
                return in_array($attribute, $visibleAttributes);
            }));
    }
}
