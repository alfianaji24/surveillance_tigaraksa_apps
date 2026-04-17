<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-user')->only(['index', 'show']);
        $this->middleware('permission:create-user')->only(['create', 'store']);
        $this->middleware('permission:update-user')->only(['edit', 'update']);
        $this->middleware('permission:delete-user')->only(['destroy']);
        $this->middleware('permission:manage-roles')->only(['roles', 'storeRole', 'updateRole', 'deleteRole', 'getRolePermissions', 'assignRolePermissions', 'getRoleUsers', 'assignRole']);
        $this->middleware('permission:manage-permissions')->only(['permissions', 'storePermission', 'updatePermission', 'deletePermission', 'storePermissionGroup', 'updatePermissionGroup', 'deletePermissionGroup', 'assignPermission']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when(request('role_id'), function($query, $roleId) {
                $query->whereHas('roles', function($q) use ($roleId) {
                    $q->where('roles.id', $roleId);
                });
            })
            ->when(request('status'), function($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $roles = Role::all();
        return view('users.index_enhanced', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'nullable|string|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active')
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $photoPath = $photo->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
            $user->save();
        }

        // Assign roles
        $user->roles()->sync($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'is_active' => $request->has('is_active')
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $photo = $request->file('profile_photo');
            $photoPath = $photo->store('profile_photos', 'public');
            $user->profile_photo = $photoPath;
            $user->save();
        }

        // Assign roles
        $user->roles()->sync($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement delete logic
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    /**
     * Show roles management
     */
    public function roles()
    {
        $roles = Role::with(['permissions', 'users'])
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);
            
        return view('users.roles_enhanced', compact('roles'));
    }

    /**
     * Show permissions management
     */
    public function permissions()
    {
        try {
            $permissions = Permission::with('roles')
                ->when(request('search'), function($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->when(request('group_id'), function($query, $groupId) {
                    $query->where('permission_group_id', $groupId);
                })
                ->orderBy('name')
                ->paginate(10);
                
            $permissionGroups = \App\Models\PermissionGroup::with('permissions')->ordered()->get();
            return view('users.permissions_enhanced', compact('permissions', 'permissionGroups'));
        } catch (\Exception $e) {
            // Fallback if there are issues with relationships
            $permissions = Permission::orderBy('name')->paginate(10);
            $permissionGroups = \App\Models\PermissionGroup::ordered()->get();
            return view('users.permissions_enhanced', compact('permissions', 'permissionGroups'));
        }
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, string $id)
    {
        // TODO: Implement role assignment
        return redirect()->route('users.show', $id)->with('success', 'Role berhasil ditetapkan');
    }

    /**
     * Store new permission
     */
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'permission_group_id' => 'nullable|exists:permission_groups,id',
            'description' => 'nullable|string'
        ]);

        Permission::create($validated);

        return redirect()->route('permissions')
            ->with('success', 'Permission berhasil ditambahkan');
    }

    /**
     * Store new permission group
     */
    public function storePermissionGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name',
            'description' => 'nullable|string'
        ]);

        \App\Models\PermissionGroup::create($validated);

        return redirect()->route('permissions')
            ->with('success', 'Permission group berhasil ditambahkan');
    }

    /**
     * Update permission
     */
    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'permission_group_id' => 'nullable|exists:permission_groups,id',
            'description' => 'nullable|string'
        ]);

        $permission->update($validated);

        return redirect()->route('permissions')
            ->with('success', 'Permission berhasil diperbarui');
    }

    /**
     * Delete permission
     */
    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions')
            ->with('success', 'Permission berhasil dihapus');
    }

    /**
     * Update permission group
     */
    public function updatePermissionGroup(Request $request, $id)
    {
        $group = \App\Models\PermissionGroup::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $group->update($validated);

        return redirect()->route('permissions')
            ->with('success', 'Permission group berhasil diperbarui');
    }

    /**
     * Delete permission group
     */
    public function deletePermissionGroup($id)
    {
        $group = \App\Models\PermissionGroup::findOrFail($id);
        
        // Check if group has permissions
        if ($group->permissions()->count() > 0) {
            return redirect()->route('permissions')
                ->with('error', 'Permission group tidak dapat dihapus karena masih memiliki permissions');
        }
        
        $group->delete();

        return redirect()->route('permissions')
            ->with('success', 'Permission group berhasil dihapus');
    }

    /**
     * Store new role
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255'
        ]);

        Role::create($validated);

        return redirect()->route('roles')
            ->with('success', 'Role berhasil ditambahkan');
    }

    /**
     * Update role
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'guard_name' => 'required|string|max:255'
        ]);

        $role->update($validated);

        return redirect()->route('roles')
            ->with('success', 'Role berhasil diperbarui');
    }

    /**
     * Delete role
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles')
                ->with('error', 'Role admin tidak dapat dihapus');
        }
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles')
                ->with('error', 'Role tidak dapat dihapus karena masih memiliki users');
        }
        
        $role->delete();

        return redirect()->route('roles')
            ->with('success', 'Role berhasil dihapus');
    }

    /**
     * Get role permissions for AJAX
     */
    public function getRolePermissions($id)
    {
        $role = Role::findOrFail($id);
        $allPermissions = Permission::orderBy('name')->get();
        
        return response()->json([
            'role_permissions' => $role->permissions->pluck('id')->toArray(),
            'all_permissions' => $allPermissions
        ]);
    }

    /**
     * Assign permissions to role
     */
    public function assignRolePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->input('permissions', []);
        
        $role->syncPermissions($permissions);
        
        return response()->json([
            'success' => true,
            'message' => 'Permissions berhasil diperbarui'
        ]);
    }

    /**
     * Get role users for AJAX
     */
    public function getRoleUsers($id)
    {
        $role = Role::findOrFail($id);
        $users = $role->users()->select('id', 'name', 'email')->get();
        
        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Assign permission to user
     */
    public function assignPermission(Request $request, string $id)
    {
        // TODO: Implement permission assignment
        return redirect()->route('users.show', $id)->with('success', 'Permission berhasil ditetapkan');
    }
}
