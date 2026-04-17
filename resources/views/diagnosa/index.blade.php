@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical"></i> Data Diagnosa PKM
                        </h5>
                        <div>
                            <a href="{{ route('diagnosa-pkm.import') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload"></i> Import Data
                            </a>
                            <a href="{{ route('diagnosa-pkm.download.template') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Cari nama pasien atau no. rekam medik...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="Sehat">Sehat</option>
                                    <option value="Rujuk">Rujuk</option>
                                    <option value="Dirawat">Dirawat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="poliFilter">
                                    <option value="">Semua Poli</option>
                                    <option value="Poli Umum">Poli Umum</option>
                                    <option value="Poli Gigi">Poli Gigi</option>
                                    <option value="Poli KIA">Poli KIA</option>
                                    <option value="Poli Lansia">Poli Lansia</option>
                                </select>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="diagnosaTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Nama Pasien</th>
                                        <th>No. RM</th>
                                        <th>Umur</th>
                                        <th>JK</th>
                                        <th>Poli</th>
                                        <th>Diagnosa</th>
                                        <th>Kode ICD-10</th>
                                        <th>Pemeriksa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($diagnosa as $index => $item)
                                        <tr>
                                            <td>{{ $diagnosa->firstItem() + $index }}</td>
                                            <td>{{ $item->tanggal_kunjungan->format('d/m/Y') }}</td>
                                            <td>{{ $item->nama_pasien }}</td>
                                            <td><strong>{{ $item->no_rekam_medik }}</strong></td>
                                            <td>{{ $item->umur_formatted }}</td>
                                            <td>
                                                <span class="badge bg-{{ $item->jenis_kelamin == 'L' ? 'primary' : 'pink' }}">
                                                    {{ $item->jenis_kelamin }}
                                                </span>
                                            </td>
                                            <td>{{ $item->poli }}</td>
                                            <td>{{ Str::limit($item->diagnosa, 30) }}</td>
                                            <td><code>{{ $item->kode_icd_10 }}</code></td>
                                            <td>{{ $item->pemeriksa }}</td>
                                            <td>
                                                <span class="badge bg-{{ $item->status == 'Sehat' ? 'success' : ($item->status == 'Rujuk' ? 'warning' : 'secondary') }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if($item->id)
                                                        <a href="{{ route('diagnosa-pkm.show', $item->id) }}" 
                                                           class="btn btn-info" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('diagnosa-pkm.destroy', $item->id) }}" 
                                                              method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" 
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                                    title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">No ID</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data</h5>
                                                <p class="text-muted">
                                                    Mulai dengan mengimport data dari file Excel atau tambah data baru.
                                                </p>
                                                <a href="{{ route('diagnosa-pkm.import') }}" class="btn btn-primary">
                                                    <i class="fas fa-upload"></i> Import Data
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($diagnosa->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Menampilkan {{ $diagnosa->firstItem() }} - {{ $diagnosa->lastItem() }} 
                                    dari {{ $diagnosa->total() }} data
                                </div>
                                {{ $diagnosa->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Search functionality
                $('#searchInput').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('#diagnosaTable tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });

                // Status filter
                $('#statusFilter').on('change', function() {
                    var value = $(this).val().toLowerCase();
                    filterTable();
                });

                // Poli filter
                $('#poliFilter').on('change', function() {
                    var value = $(this).val().toLowerCase();
                    filterTable();
                });

                function filterTable() {
                    var statusValue = $('#statusFilter').val().toLowerCase();
                    var poliValue = $('#poliFilter').val().toLowerCase();
                    var searchValue = $('#searchInput').val().toLowerCase();

                    $('#diagnosaTable tbody tr').each(function() {
                        var row = $(this);
                        var status = row.find('td:eq(10)').text().toLowerCase();
                        var poli = row.find('td:eq(9)').text().toLowerCase();
                        var text = row.text().toLowerCase();

                        var showRow = true;

                        if (statusValue && status !== statusValue) {
                            showRow = false;
                        }

                        if (poliValue && poli !== poliValue) {
                            showRow = false;
                        }

                        if (searchValue && text.indexOf(searchValue) === -1) {
                            showRow = false;
                        }

                        row.toggle(showRow);
                    });
                }
            });
        </script>
    @endpush
@endsection
