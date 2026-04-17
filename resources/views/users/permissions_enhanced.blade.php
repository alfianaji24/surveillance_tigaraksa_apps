@extends('layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Permissions Management</h4>
                    <p class="text-muted mb-0">Kelola hak akses sistem</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                        <i class="fas fa-folder-plus me-2"></i>Tambah Group
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i class="fas fa-plus me-2"></i>Tambah Permission
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Groups Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Permission Groups</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <!-- Search Groups -->
                    <form action="{{ route('permissions') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="group_search" value="{{ request('group_search') }}" placeholder="Search Group...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Groups List -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Group Name</th>
                                    <th>Permissions</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($permissionGroups->count() > 0)
                                    @foreach($permissionGroups as $index => $group)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-medium">{{ $group->name }}</div>
                                                @if($group->description)
                                                    <small class="text-muted">{{ Str::limit($group->description, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $group->permissions->count() }} permissions</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-success editGroup" data-id="{{ $group->id }}" data-name="{{ $group->name }}" data-description="{{ $group->description ?? '' }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger deleteGroup" data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Belum ada permission groups</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="text-primary mb-1">{{ $permissions->total() }}</h3>
                                    <small class="text-muted">Total Permissions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="text-success mb-1">{{ $permissionGroups->count() }}</h3>
                                    <small class="text-muted">Permission Groups</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="text-info mb-1">{{ \Spatie\Permission\Models\Role::count() }}</h3>
                                    <small class="text-muted">Total Roles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="text-warning mb-1">{{ \App\Models\User::count() }}</h3>
                                    <small class="text-muted">Total Users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Permissions</h5>
                </div>
                <div class="card-body">
                    <!-- Filter Permissions -->
                    <form action="{{ route('permissions') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search permissions...">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="group_id">
                                    <option value="">All Groups</option>
                                    @foreach($permissionGroups as $group)
                                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Permissions Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Permission Name</th>
                                    <th>Group</th>
                                    <th>Assigned To</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($permissions->count() > 0)
                                    @foreach($permissions as $index => $permission)
                                        <tr>
                                            <td>{{ $permissions->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-shield-alt text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-medium">{{ strtolower($permission->name) }}</div>
                                                        @if($permission->description)
                                                            <small class="text-muted">{{ Str::limit($permission->description, 40) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(isset($permission->group) && $permission->group)
                                                    <span class="badge bg-info">{{ $permission->group->name }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No Group</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($permission->roles->count() > 0)
                                                        @foreach($permission->roles->take(3) as $role)
                                                            <span class="badge bg-secondary">{{ ucwords($role->name) }}</span>
                                                        @endforeach
                                                        @if($permission->roles->count() > 3)
                                                            <span class="badge bg-light text-dark">+{{ $permission->roles->count() - 3 }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-success editPermission" 
                                                            data-id="{{ $permission->id }}" 
                                                            data-name="{{ $permission->name }}" 
                                                            data-group-id="{{ $permission->permission_group_id ?? '' }}"
                                                            data-description="{{ $permission->description ?? '' }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger deletePermission" 
                                                            data-id="{{ $permission->id }}" 
                                                            data-name="{{ $permission->name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-3">Belum ada permissions</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                                <i class="fas fa-plus me-2"></i>Tambah Permission Pertama
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($permissions->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $permissions->firstItem() }} hingga {{ $permissions->lastItem() }} dari {{ $permissions->total() }} permissions
                            </div>
                            {{ $permissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Permission Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="permissionName" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="permissionName" name="name" required placeholder="Contoh: read-user, create-penyakit">
                            <div class="form-text">Gunakan format: action-resource (contoh: read-user)</div>
                        </div>
                        <div class="mb-3">
                            <label for="permissionGroup" class="form-label">Permission Group</label>
                            <select class="form-select" id="permissionGroup" name="permission_group_id">
                                <option value="">Pilih Group (opsional)</option>
                                @foreach($permissionGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="permissionDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="permissionDescription" name="description" rows="3" placeholder="Deskripsi permission ini..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Permission Modal -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Permission
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="#" id="editPermissionForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editPermissionId" name="id">
                        <div class="mb-3">
                            <label for="editPermissionName" class="form-label">Nama Permission <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editPermissionName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPermissionGroup" class="form-label">Permission Group</label>
                            <select class="form-select" id="editPermissionGroup" name="permission_group_id">
                                <option value="">Pilih Group (opsional)</option>
                                @foreach($permissionGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editPermissionDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editPermissionDescription" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Permission Group Modal -->
    <div class="modal fade" id="addGroupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-folder-plus me-2"></i>Tambah Permission Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('permission-groups.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="groupName" class="form-label">Nama Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="groupName" name="name" required placeholder="Contoh: User Management, Data Entry">
                        </div>
                        <div class="mb-3">
                            <label for="groupDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="groupDescription" name="description" rows="3" placeholder="Deskripsi group ini..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Permission Group Modal -->
    <div class="modal fade" id="editGroupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Permission Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="#" id="editGroupForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editGroupId" name="id">
                        <div class="mb-3">
                            <label for="editGroupName" class="form-label">Nama Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editGroupName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGroupDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editGroupDescription" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Edit Permission
document.querySelectorAll('.editPermission').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const groupId = this.dataset.groupId;
        const description = this.dataset.description;
        
        document.getElementById('editPermissionId').value = id;
        document.getElementById('editPermissionName').value = name;
        document.getElementById('editPermissionGroup').value = groupId;
        document.getElementById('editPermissionDescription').value = description;
        
        document.getElementById('editPermissionForm').setAttribute('action', `/permissions/${id}`);
        
        new bootstrap.Modal(document.getElementById('editPermissionModal')).show();
    });
});

// Delete Permission
document.querySelectorAll('.deletePermission').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if(confirm(`Apakah Anda yakin ingin menghapus permission "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/permissions/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});

// Edit Group
document.querySelectorAll('.editGroup').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const description = this.dataset.description;
        
        document.getElementById('editGroupId').value = id;
        document.getElementById('editGroupName').value = name;
        document.getElementById('editGroupDescription').value = description;
        
        document.getElementById('editGroupForm').setAttribute('action', `/permission-groups/${id}`);
        
        new bootstrap.Modal(document.getElementById('editGroupModal')).show();
    });
});

// Delete Group
document.querySelectorAll('.deleteGroup').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if(confirm(`Apakah Anda yakin ingin menghapus group "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/permission-groups/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endsection
