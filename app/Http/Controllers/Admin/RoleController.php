<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use App\Http\Forms\RoleForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('admin.roles.index')
            ->with('roles', $roles);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = app(RoleForm::class)->create();

        return view('admin.roles.create')
            ->with('form', $form);
    }

    /**
     * Show the form for creating a Role.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required',
        ]);

        $role = Role::create([
            'name' => strtolower($request->label),
            'label' => $request->label,
            'permissions' => array_keys($request->permissions ?? []),
        ]);

        return redirect(route('admin.roles.edit', $role->id))->with('message', 'Role Created');
    }

    /**
     * Show the form for editing the Role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $form = app(RoleForm::class)->edit($role);

        return view('admin.roles.edit')
            ->with('form', $form)
            ->with('role', $role);
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

            return back()->with('message', 'Successfully updated');
        } catch (Exception $e) {
            return back()->with('errors', ['Failed to update']);
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
            return back()->withErrors('Cannot delete admin.');
        }

        $role->users()->detach();

        $role->delete();

        return redirect(route('admin.roles.index'))->with('message', 'Successfully deleted');
    }
}
