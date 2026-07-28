<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use App\Models\Role;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return View
     */
    public function index()
    {
        $roles = Role::paginate(10);

        return view('admin.roles.index')->withRoles($roles);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Show the form for creating a Role.
     *
     * @return RedirectResponse
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
     * @return View
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit')->withRole($role);
    }

    /**
     * Update the Role in storage.
     *
     * @return RedirectResponse
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
     * @return RedirectResponse
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
