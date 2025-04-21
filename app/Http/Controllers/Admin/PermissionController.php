<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions']);
        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dibuat');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus');
    }
}
