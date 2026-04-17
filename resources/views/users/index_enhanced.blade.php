@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Users Management</h4>
                    <p class="text-muted mb-0">Kelola pengguna sistem</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i>Tambah User
                </button>
            </div>
        </div>
    </div>

    
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <form action="{{ route('users.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   placeholder="Search Name or Email">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="role_id" id="role_id" class="form-select">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(request('role_id')==$role->id)>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" @selected(request('status')=='active')">Active</option>
                            <option value="inactive" @selected(request('status')=='inactive')">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $users->total() }}</h4>
                            <small>Total Users</small>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ \App\Models\User::where('email_verified_at', '!=', null)->count() }}</h4>
                            <small>Verified</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $roles->count() }}</h4>
                            <small>Total Roles</small>
                        </div>
                        <i class="fas fa-user-tag fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ \App\Models\User::where('last_login_at', '>', now()->subDays(7))->count() }}</h4>
                            <small>Active This Week</small>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Users Management
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="row">
                            @foreach($users as $user)
                                <div class="col-lg-6 col-xl-4 mb-3">
                                    <div class="card border shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <img src="{{ $user->profile_photo_url }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="rounded-circle me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #e9ecef;">
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">{{ $user->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                                                    @if($user->username)
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-at me-1"></i>{{ $user->username }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item editUser" href="#" 
                                                               data-id="{{ $user->id }}" 
                                                               data-name="{{ $user->name }}" 
                                                               data-email="{{ $user->email }}">
                                                                <i class="fas fa-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item viewUser" href="#" 
                                                               data-id="{{ $user->id }}">
                                                                <i class="fas fa-eye me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger deleteUser" href="#" 
                                                               data-id="{{ $user->id }}" 
                                                               data-name="{{ $user->name }}">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- Roles -->
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">Roles:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Status Info -->
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="border-end">
                                                        @if($user->email_verified_at)
                                                            <i class="fas fa-check-circle text-success"></i>
                                                            <small class="d-block text-muted">Verified</small>
                                                        @else
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                            <small class="d-block text-muted">Not Verified</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                </div>
                                                <div class="col-4">
                                                    @if($user->is_active ?? true)
                                                        <i class="fas fa-toggle-on text-success"></i>
                                                        <small class="d-block text-muted">Active</small>
                                                    @else
                                                        <i class="fas fa-toggle-off text-warning"></i>
                                                        <small class="d-block text-muted">Inactive</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} users
                            </div>
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada users</h5>
                            <p class="text-muted mb-3">Belum ada users yang terdaftar</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-2"></i>Tambah User Pertama
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah User Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="userName" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="userName" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="userEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="userProfilePhoto" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="userProfilePhoto" name="profile_photo" accept="image/*">
                                    <div class="form-text">Opsional. Maksimal 2MB</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="userUsername" name="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userPhone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="userPhone" name="phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="userPassword" name="password" required>
                                    <div class="form-text">Minimal 8 karakter</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userPasswordConfirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="userPasswordConfirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="userRoles" class="form-label">Roles <span class="text-danger">*</span></label>
                                    <select class="form-select" id="userRoles" name="roles[]" multiple required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Pilih satu atau lebih roles (Ctrl/Cmd + Click)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="userActive" name="is_active" checked>
                                        <label class="form-check-label" for="userActive">
                                            User Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="#" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editUserName" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editUserName" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editUserEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="editUserEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editUserProfilePhoto" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="editUserProfilePhoto" name="profile_photo" accept="image/*">
                                    <div class="form-text">Opsional. Kosongkan jika tidak ingin mengubah</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUserUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="editUserUsername" name="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUserPhone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="editUserPhone" name="phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUserPassword" class="form-label">Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" id="editUserPassword" name="password">
                                    <div class="form-text">Minimal 8 karakter</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUserRoles" class="form-label">Roles <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editUserRoles" name="roles[]" multiple required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editUserActive" name="is_active">
                                        <label class="form-check-label" for="editUserActive">
                                            User Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

// Edit User
document.querySelectorAll('.editUser').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const email = this.dataset.email;
        
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;
        
        document.getElementById('editUserForm').setAttribute('action', `/users/${id}`);
        
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    });
});

// Delete User
document.querySelectorAll('.deleteUser').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if(confirm(`Apakah Anda yakin ingin menghapus user "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});

// View User Details
document.querySelectorAll('.viewUser').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        
        // Load user details via AJAX
        fetch(`/users/${id}`)
            .then(response => response.text())
            .then(html => {
                // Create a temporary modal to show user details
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">User Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${html}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                new bootstrap.Modal(modal).show();
                
                // Remove modal after hidden
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.removeChild(modal);
                });
            });
    });
});
</script>
@endsection
