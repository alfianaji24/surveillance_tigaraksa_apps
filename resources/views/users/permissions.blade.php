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

    <div class="row">
        <!-- Permission Groups -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Groups</h5>
                </div>
                <div class="card-body p-0">
                    @if($permissionGroups->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($permissionGroups as $group)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $group->name }}</h6>
                                        <small class="text-muted">{{ $group->permissions->count() }} permissions</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada permission groups</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                                <i class="fas fa-plus me-2"></i>Tambah Group Pertama
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Statistics</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $permissions->total() }}</h4>
                                <small class="text-muted">Total Permissions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $permissionGroups->count() }}</h4>
                            <small class="text-muted">Groups</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">All Permissions</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search permissions..." id="searchPermission">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <th>Group</th>
                                    <th>Assigned To</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($permissions->count() > 0)
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-shield-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $permission->name }}</div>
                                                        @if($permission->description)
                                                            <small class="text-muted">{{ Str::limit($permission->description, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(isset($permission->group) && $permission->group)
                                                    <span class="badge bg-info">{{ $permission->group->name }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No group</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($permission->roles->count() > 0)
                                                        @foreach($permission->roles->take(2) as $role)
                                                            <span class="badge bg-secondary">{{ $role->name }}</span>
                                                        @endforeach
                                                        @if($permission->roles->count() > 2)
                                                            <span class="badge bg-light text-dark">+{{ $permission->roles->count() - 2 }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Assign">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
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
</div>

<script>
// Search functionality
document.getElementById('searchPermission').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Add Group button functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add button to permission groups card header
    const groupCardHeader = document.querySelector('.card-header h5');
    if (groupCardHeader) {
        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.className = 'btn btn-sm btn-outline-primary float-end';
        addButton.setAttribute('data-bs-toggle', 'modal');
        addButton.setAttribute('data-bs-target', '#addGroupModal');
        addButton.innerHTML = '<i class="fas fa-plus"></i>';
        groupCardHeader.parentElement.appendChild(addButton);
    }
});
</script>
@endsection
