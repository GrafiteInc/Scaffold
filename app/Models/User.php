<?php

namespace App\Models;

use App\Models\Concerns\HasRoles;
use App\Models\Concerns\HasTeams;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\HasActivity;
use App\Notifications\ResetPassword;
use App\Models\Concerns\HasPermissions;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'avatar',
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
        'avatar_url',
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
     * Avatar Image Url
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if (!is_null($this->avatar)) {
            return url(Storage::url($this->avatar));
        }

        return app(InitialAvatar::class)
            ->name($this->name)
            ->height(250)
            ->width(250)
            ->background('#eeeeee')
            ->color('#464349')
            ->generate()
            ->encode('data-url');
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
