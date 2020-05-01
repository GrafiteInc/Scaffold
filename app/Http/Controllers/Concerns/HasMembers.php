<?php

namespace App\Http\Controllers\Concerns;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inviteMember(Request $request, $model)
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
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMember($model, User $member)
    {
        try {
            $result = $this->service->remove($member, $model);

            if ($result) {
                return back()->with('message', 'Success, the member was removed');
            }

            return back()->withErrors(['Failed to remove member']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
