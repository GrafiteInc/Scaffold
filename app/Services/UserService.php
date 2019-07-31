<?php

namespace App\Services;

use Auth;
use Session;
use Exception;
use App\Models\User;

class UserService
{
    /**
     * User model
     *
     * @var User
     */
    public $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Update a user's profile
     *
     * @param  int $userId User Id
     *
     * @return User
     */
    public function update($user, $payload)
    {
        $user->update($payload);

        return $user->fresh();
    }

    // /**
    //  * Invite a new member
    //  * @param  array $info
    //  * @return void
    //  */
    // public function invite($info)
    // {
    //     $password = substr(md5(rand(1111, 9999)), 0, 10);

    //     return DB::transaction(function () use ($password, $info) {
    //         $user = $this->model->create([
    //             'email' => $info['email'],
    //             'name' => $info['name'],
    //             'password' => bcrypt($password)
    //         ]);

    //         return $this->create($user, $password, $info['roles'], true);
    //     });
    // }

    /**
     * Destroy the profile
     *
     * @param  int $id
     * @return bool
     */
    public function destroy($user)
    {
        // try {
        //     return DB::transaction(function () use ($id) {
        //         $this->unassignAllRoles($id);
        //         $this->leaveAllTeams($id);

        //         $userMetaResult = $this->userMeta->where('user_id', $id)->delete();
        //         $userResult = $this->model->find($id)->delete();

        //         return ($userMetaResult && $userResult);
        //     });
        // } catch (Exception $e) {
        //     throw new Exception("We were unable to delete this profile", 1);
        // }
    }

    /**
     * Switch user login
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchToUser($id)
    {
        try {
            $user = $this->model->find($id);
            Session::put('original_user', Auth::id());
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            throw new Exception("Error logging in as user", 1);
        }
    }

    /**
     * Switch back
     *
     * @param  integer $id
     * @return boolean
     */
    public function switchUserBack()
    {
        try {
            $original = Session::pull('original_user');

            $user = $this->model->find($original);

            Auth::login($user);

            return true;
        } catch (Exception $e) {
            throw new Exception("Error returning to your user", 1);
        }
    }
}
