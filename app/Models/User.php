<?php

namespace App\Models;

use App\Http\Forms\UserForm;
use App\Models\Concerns\DatabaseSearchable;
use App\Models\Concerns\HasActivity;
use App\Models\Concerns\HasAvatar;
use App\Models\Concerns\HasCachedValues;
use App\Models\Concerns\HasPermissions;
use App\Models\Concerns\HasRoles;
use App\Models\Concerns\HasSessions;
use App\Models\Concerns\HasSubscription;
use App\Models\Concerns\HasTeams;
use App\Models\Concerns\HasTwoFactor;
use App\Notifications\ResetPassword;
use Grafite\Forms\Traits\HasForm;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable;
    use Notifiable;
    use HasTeams;
    use HasForm;
    use HasActivity;
    use HasAvatar;
    use HasApiTokens;
    use HasCachedValues;
    use HasSubscription;
    use HasRoles;
    use HasPermissions;
    use HasFactory;
    use HasTwoFactor;
    use DatabaseSearchable;
    use HasSessions;

    public $form = UserForm::class;

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
        'allow_email_based_notifications',
        'email_verified_at',
        'billing_email',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'state',
        'country',
        'two_factor_platform',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_confirmed_at',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_platform',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_confirmed_at',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Appends.
     *
     * @var array
     */
    public $appends = [
        'avatar_url',
    ];

    /**
     * User Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites()
    {
        return $this->hasMany(Invite::class, 'email', 'email')
            ->whereNotNull('model_id');
    }

    /**
     * Get the email address used to create the customer in Stripe.
     *
     * @return string|null
     */
    public function stripeEmail()
    {
        return $this->billing_email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function notifyPasswordReset($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Prepare a payload for the JS session data.
     *
     * @return string|false
     */
    public function jsonSessionData()
    {
        $visibleAttributes = [
            'id',
            'name',
            'email',
        ];

        return json_encode(collect($this->toArray())
            ->filter(function ($value, $attribute) use ($visibleAttributes) {
                return in_array($attribute, $visibleAttributes);
            }));
    }
}
