<?php

namespace App\Http\Controllers\User\Concerns;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

trait HasMembers
{
    /**
     * Invite a member
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request, $model)
    {
        try {
            if ($this->service->invite($model, $request->email)) {
                return back()->with('message', 'Successfully sent invite');
            }

            return back()->withErrors(['Failed to send invite']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Leave
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function leave($model)
    {
        try {
            if ($this->service->leave($model)) {
                return back()->with('message', 'Success, your membership was removed');
            }

            return back()->withErrors(['Failed to remove membership']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove member
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function remove($model, User $user)
    {
        try {
            $result = $this->service->remove($user, $model);

            if ($result) {
                return back()->with('message', 'Success, the member was removed');
            }

            return back()->withErrors(['Failed to remove member']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}