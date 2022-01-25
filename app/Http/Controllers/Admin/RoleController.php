<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::paginate(10);

        return view('admin.roles.index')->withRoles($roles);
    }

    /**
     * Show the form for inviting a customer.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Show the form for creating a Role.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        $role = Role::create([
            'name' => strtolower($request->label),
            'label' => $request->label,
            'permissions' => array_keys($request->permissions ?? []),
        ]);

        return redirect()->route('admin.roles.edit', $role->id)->with('message', 'Role Created');
    }

    /**
     * Show the form for editing the Role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit')->withRole($role);
    }

    /**
     * Update the Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        try {
            $role = $role->update([
                'label' => $request->label,
                'name' => strtolower($request->label),
                'permissions' => array_keys($request->permissions ?? []),
            ]);

            return redirect()->back()->with('message', 'Successfully updated');
        } catch (Exception $e) {
            return redirect()->back()->with('errors', ['Failed to update']);
        }
    }

    /**
     * Remove the Role from storage.
     * Force the logout of users who
     * had that role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->back()->withErrors('Cannot delete admin.');
        }

        $role->users()->detach();

        $role->delete();

        return redirect()->route('admin.roles.index')->with('message', 'Successfully deleted');
    }
}
