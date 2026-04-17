<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-permissions');
    }

    public function index(): View
    {
        $permissions = Permission::with('permissionGroup')->get();
        $groups = PermissionGroup::all();
        return view('permissions.index', compact('permissions', 'groups'));
    }

    public function create(): View
    {
        $groups = PermissionGroup::all();
        return view('permissions.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'permission_group_id' => 'nullable|exists:permission_groups,id'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'permission_group_id' => $request->permission_group_id
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission): View
    {
        $permission->load('permissionGroup');
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        $groups = PermissionGroup::all();
        return view('permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'permission_group_id' => 'nullable|exists:permission_groups,id'
        ]);

        $permission->update([
            'name' => $request->name,
            'permission_group_id' => $request->permission_group_id
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
