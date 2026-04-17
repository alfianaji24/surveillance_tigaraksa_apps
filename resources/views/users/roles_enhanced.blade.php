@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Roles Management</h4>
                    <p class="text-muted mb-0">Kelola peran pengguna sistem</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="fas fa-plus me-2"></i>Tambah Role
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Roles</h5>
                </div>
                <div class="card-body">
                    <!-- Search Roles -->
                    <form action="{{ route('users.roles') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search Role Name...">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Roles Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Role Name</th>
                                    <th>Guard</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($roles->count() > 0)
                                    @foreach($roles as $index => $role)
                                        <tr>
                                            <td>{{ $roles->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tag text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-medium">{{ ucwords($role->name) }}</div>
                                                        @if($role->name == 'superadmin')
                                                            <small class="text-muted">System Administrator</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $role->guard_name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $role->permissions->count() }} permissions</span>
                                                @if($role->permissions->count() > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-info ms-1 viewPermissions" 
                                                            data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $role->users->count() }} users</span>
                                                @if($role->users->count() > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-success ms-1 viewUsers" 
                                                            data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                                        <i class="fas fa-users"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info managePermissions" 
                                                            data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}"
                                                            title="Manage Permissions">
                                                        <i class="fas fa-user-secret"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success editRole" 
                                                            data-id="{{ $role->id }}" 
                                                            data-name="{{ $role->name }}"
                                                            title="Edit Role">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($role->name != 'admin')
                                                    <button type="button" class="btn btn-outline-danger deleteRole" 
                                                            data-id="{{ $role->id }}" 
                                                            data-name="{{ $role->name }}"
                                                            title="Delete Role">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-3">Belum ada roles</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                                <i class="fas fa-plus me-2"></i>Tambah Role Pertama
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($roles->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $roles->firstItem() }} hingga {{ $roles->lastItem() }} dari {{ $roles->total() }} roles
                            </div>
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Role Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('users.roles.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="roleName" name="name" required placeholder="Contoh: Admin, Manager, User">
                            <div class="form-text">Gunakan nama yang deskriptif dan singkat</div>
                        </div>
                        <div class="mb-3">
                            <label for="roleGuard" class="form-label">Guard</label>
                            <select class="form-select" id="roleGuard" name="guard_name">
                                <option value="web" selected>Web</option>
                                <option value="api">API</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="#" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                                                <div class="mb-3">
                            <label for="editRoleName" class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editRoleName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRoleGuard" class="form-label">Guard</label>
                            <select class="form-select" id="editRoleGuard" name="guard_name">
                                <option value="web">Web</option>
                                <option value="api">API</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Permissions Modal -->
    <div class="modal fade" id="managePermissionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-secret me-2"></i>Manage Permissions - <span id="modalRoleName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="managePermissionsForm">
                        @csrf
                        <input type="hidden" id="manageRoleId" name="role_id">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">
                                            <strong>Select All Permissions</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="row" id="permissionsList">
                                    <!-- Permissions will be loaded here via JavaScript -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="savePermissions">
                        <i class="fas fa-save me-2"></i>Simpan Permissions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Permissions Modal -->
    <div class="modal fade" id="viewPermissionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Permissions - <span id="viewRoleName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="permissionsListDisplay">
                        <!-- Permissions will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Users Modal -->
    <div class="modal fade" id="viewUsersModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-users me-2"></i>Users - <span id="viewUsersRoleName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="usersListDisplay">
                        <!-- Users will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Edit Role
document.querySelectorAll('.editRole').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        document.getElementById('editRoleName').value = name;
        document.getElementById('editRoleGuard').value = 'web';
        
        document.getElementById('editRoleForm').action = `/users/roles/${id}`;
        
        new bootstrap.Modal(document.getElementById('editRoleModal')).show();
    });
});

// Delete Role
document.querySelectorAll('.deleteRole').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if(confirm(`Apakah Anda yakin ingin menghapus role "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/roles/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});

// Manage Permissions
document.querySelectorAll('.managePermissions').forEach(button => {
    button.addEventListener('click', function() {
        const roleId = this.dataset.roleId;
        const roleName = this.dataset.roleName;
        
        document.getElementById('manageRoleId').value = roleId;
        document.getElementById('modalRoleName').textContent = roleName;
        
        // Load permissions
        fetch(`/users/roles/${roleId}/permissions`)
            .then(response => response.json())
            .then(data => {
                const permissionsList = document.getElementById('permissionsList');
                permissionsList.innerHTML = '';
                
                // Group permissions by category
                const grouped = {};
                data.all_permissions.forEach(permission => {
                    const category = permission.name.split('-')[0];
                    if (!grouped[category]) {
                        grouped[category] = [];
                    }
                    grouped[category].push(permission);
                });
                
                Object.keys(grouped).forEach(category => {
                    const categoryDiv = document.createElement('div');
                    categoryDiv.className = 'col-md-6 mb-3';
                    categoryDiv.innerHTML = `
                        <h6 class="text-primary mb-2">${category.toUpperCase()}</h6>
                        <div class="border rounded p-2">
                            ${grouped[category].map(permission => `
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                           name="permissions[]" value="${permission.id}" 
                                           id="perm_${permission.id}"
                                           ${data.role_permissions.includes(permission.id) ? 'checked' : ''}>
                                    <label class="form-check-label" for="perm_${permission.id}">
                                        ${permission.name}
                                    </label>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    permissionsList.appendChild(categoryDiv);
                });
                
                new bootstrap.Modal(document.getElementById('managePermissionsModal')).show();
            });
    });
});

// Select All Permissions
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Save Permissions
document.getElementById('savePermissions').addEventListener('click', function() {
    const form = document.getElementById('managePermissionsForm');
    const formData = new FormData(form);
    
    fetch(`/users/roles/${document.getElementById('manageRoleId').value}/permissions`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('managePermissionsModal')).hide();
            location.reload();
        } else {
            alert('Terjadi kesalahan: ' + data.message);
        }
    });
});

// View Permissions
document.querySelectorAll('.viewPermissions').forEach(button => {
    button.addEventListener('click', function() {
        const roleId = this.dataset.roleId;
        const roleName = this.dataset.roleName;
        
        document.getElementById('viewRoleName').textContent = roleName;
        
        fetch(`/users/roles/${roleId}/permissions`)
            .then(response => response.json())
            .then(data => {
                const permissionsListDisplay = document.getElementById('permissionsListDisplay');
                
                if (data.role_permissions.length > 0) {
                    const permissions = data.all_permissions.filter(p => data.role_permissions.includes(p.id));
                    permissionsListDisplay.innerHTML = `
                        <div class="list-group">
                            ${permissions.map(permission => `
                                <div class="list-group-item">
                                    <i class="fas fa-shield-alt text-primary me-2"></i>
                                    ${permission.name}
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    permissionsListDisplay.innerHTML = `
                        <div class="text-center py-3">
                            <i class="fas fa-shield-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Role ini tidak memiliki permissions</p>
                        </div>
                    `;
                }
                
                new bootstrap.Modal(document.getElementById('viewPermissionsModal')).show();
            });
    });
});

// View Users
document.querySelectorAll('.viewUsers').forEach(button => {
    button.addEventListener('click', function() {
        const roleId = this.dataset.roleId;
        const roleName = this.dataset.roleName;
        
        document.getElementById('viewUsersRoleName').textContent = roleName;
        
        fetch(`/users/roles/${roleId}/users`)
            .then(response => response.json())
            .then(data => {
                const usersListDisplay = document.getElementById('usersListDisplay');
                
                if (data.users.length > 0) {
                    usersListDisplay.innerHTML = `
                        <div class="list-group">
                            ${data.users.map(user => `
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <div>
                                            <div class="fw-medium">${user.name}</div>
                                            <small class="text-muted">${user.email}</small>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    usersListDisplay.innerHTML = `
                        <div class="text-center py-3">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Role ini tidak memiliki users</p>
                        </div>
                    `;
                }
                
                new bootstrap.Modal(document.getElementById('viewUsersModal')).show();
            });
    });
});
</script>
@endsection
