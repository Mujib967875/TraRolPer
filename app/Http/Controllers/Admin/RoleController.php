<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Admin;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'admin',
        ]);

        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role dihapus!');
    }

    public function assignRoleForm() {
        $admins = Admin::all();
        $roles = Role::all();
        return view('admin.roles.assign', compact('admins', 'roles'));
    }

    public function assignRoleSubmit(Request $request) {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'role' => 'required'
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $admin->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Role berhasil diberikan ke user!');
    }

    public function removeRole(Admin $admin, $roleName)
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $admin->removeRole($role);

        return redirect()->back()->with('success', 'Role berhasil dihapus dari user.');
    }

}
