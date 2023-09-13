<?php

namespace App\Models;

use App\Http\Forms\UserForm;
use Laravel\Cashier\Billable;
use App\Models\Concerns\HasRoles;
use App\Models\Concerns\HasTeams;
use Grafite\Forms\Traits\HasForm;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\HasAvatar;
use App\Models\Concerns\HasActivity;
use App\Models\Concerns\HasSessions;
use App\Notifications\ResetPassword;
use App\Models\Concerns\HasTwoFactor;
use App\Models\Concerns\HasPermissions;
use App\Models\Concerns\HasCachedValues;
use App\Models\Concerns\HasSubscription;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\DatabaseSearchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Grafite\Support\Models\Concerns\CanAccessFeatures;
use Grafite\Support\Models\Concerns\HasJavascriptData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    use CanAccessFeatures;
    use HasJavascriptData;

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
}
