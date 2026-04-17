@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Data Pasien</h4>
                    <p class="text-muted mb-0">Kelola data kunjungan pasien</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pasien.import') }}" class="btn btn-info">
                        <i class="fas fa-file-excel me-2"></i>Import Data
                    </a>
                    <a href="{{ route('pasien.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $pasiens->total() }}</h4>
                            <small>Total Pasien</small>
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
                            <h4 class="mb-0">{{ \App\Models\Pasien::whereDate('tanggal_kunjungan', today())->count() }}</h4>
                            <small>Hari Ini</small>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ \App\Models\Pasien::where('status', 'Dirujuk')->count() }}</h4>
                            <small>Dirujuk</small>
                        </div>
                        <i class="fas fa-hospital fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ \App\Models\Pasien::where('jenis_pasien', 'BPJS')->count() }}</h4>
                            <small>Pasien BPJS</small>
                        </div>
                        <i class="fas fa-id-card fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <form action="{{ route('pasien.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   placeholder="Search Nama/No. RM">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="poli" class="form-select">
                            <option value="">Semua Poli</option>
                            @foreach($polis as $poli)
                            <option value="{{ $poli->id }}" @selected(request('poli')==$poli->id)>{{ $poli->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Dilayani" @selected(request('status')=='Dilayani')">Dilayani</option>
                            <option value="Dirujuk" @selected(request('status')=='Dirujuk')">Dirujuk</option>
                            <option value="Lain-Lain" @selected(request('status')=='Lain-Lain')">Lain-Lain</option>
                        </select>
                    </div>
                                        <div class="col-md-2">
                        <select name="status_active" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" @selected(request('status_active')=='1')">Active</option>
                            <option value="0" @selected(request('status_active')=='0')">Non-Active</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="tanggal" value="{{ request('tanggal') }}" 
                               placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pasien List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Pasien
                    </h5>
                </div>
                <div class="card-body">
                    @if($pasiens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Poli</th>
                                        <th>Jenis Pasien</th>
                                        <th>Diagnosa ICD-10</th>
                                        <th>Status</th>
                                        <th>Status Aktif</th>
                                        <th>Pemeriksa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pasiens as $pasien)
                                    <tr>
                                        <td>{{ $pasien->tanggal_kunjungan->format('d/m/Y') }}</td>
                                        <td>{{ $pasien->no_rekam_medik }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $pasien->nama_pasien }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $pasien->umur }}, {{ $pasien->jenis_kelamin == 'L' ? 'L' : 'P' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $pasien->poli }}</td>
                                        <td>
                                            <span class="badge bg-{{ $pasien->jenis_pasien == 'BPJS' ? 'success' : 'primary' }}">
                                                {{ $pasien->jenis_pasien }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($pasien->icd10Codes->count() > 0)
                                                @foreach($pasien->icd10Codes as $icd10)
                                                    <span class="badge bg-info me-1">{{ $icd10->code }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pasien->status == 'Dilayani')
                                                <span class="badge bg-success">{{ $pasien->status }}</span>
                                            @elseif($pasien->status == 'Dirujuk')
                                                <span class="badge bg-warning">{{ $pasien->status }}</span>
                                            @else
                                                <span class="badge bg-info">{{ $pasien->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pasien->status_active)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times-circle me-1"></i>Non-Active
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $pasien->pemeriksa }}</td>
                                        <td>
                                            <a href="{{ route('pasien.edit', $pasien->hash_id) }}" 
                                               class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $pasiens->firstItem() }} hingga {{ $pasiens->lastItem() }} dari {{ $pasiens->total() }} pasien
                            </div>
                            {{ $pasiens->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data pasien</h5>
                            <p class="text-muted mb-3">Belum ada data pasien yang terdaftar</p>
                            <a href="{{ route('pasien.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Pasien Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-pasien').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if(confirm(`Apakah Anda yakin ingin menghapus data pasien "${name}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pasien/${id}`;
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
