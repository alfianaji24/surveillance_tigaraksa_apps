<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-permission-groups');
    }

    public function index(): View
    {
        $groups = PermissionGroup::with('permissions')->get();
        return view('permission-groups.index', compact('groups'));
    }

    public function create(): View
    {
        return view('permission-groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permission_groups,name',
            'description' => 'nullable|string'
        ]);

        PermissionGroup::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permission-groups.index')
            ->with('success', 'Permission group created successfully.');
    }

    public function show($id): View
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        $permissionGroup->load('permissions');
        return view('permission-groups.show', compact('permissionGroup'));
    }

    public function edit($id): View
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        return view('permission-groups.edit', compact('permissionGroup'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|unique:permission_groups,name,' . $permissionGroup->id,
            'description' => 'nullable|string'
        ]);

        $permissionGroup->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permission-groups.index')
            ->with('success', 'Permission group updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $permissionGroup = PermissionGroup::findOrFail($id);
        
        // Check if group has permissions
        if ($permissionGroup->permissions()->count() > 0) {
            return redirect()->route('permission-groups.index')
                ->with('error', 'Cannot delete permission group that contains permissions.');
        }

        $permissionGroup->delete();

        return redirect()->route('permission-groups.index')
            ->with('success', 'Permission group deleted successfully.');
    }
}
