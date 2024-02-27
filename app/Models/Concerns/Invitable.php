<?php

namespace App\Models\Concerns;

use App\Models\Invite;
use App\Services\InviteService;
use Exception;

trait Invitable
{
    /**
     * Invite a user to this.
     *
     * @param  string  $email
     * @param  string  $message
     * @return \App\Models\Invite
     */
    public function invite($email, $message)
    {
        if (! property_exists($this, 'relationship')) {
            throw new Exception('The model must have a relationship property that connects the user for attaching.', 1);
        }

        return app(InviteService::class)->create($this, $email, $message);
    }

    /**
     * Model Invites.
     *
     * @return mixed
     */
    public function invites()
    {
        return $this->hasMany(Invite::class, 'model_id', 'id');
    }
}
