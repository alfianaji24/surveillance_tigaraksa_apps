@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Import Data Diagnosa PKM') }}</h5>
                        <a href="{{ route('diagnosa-pkm.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
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

                        @if (!$zipArchiveAvailable)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <h6><i class="fas fa-exclamation-triangle"></i> Peringatan: Extension ZipArchive Tidak Tersedia</h6>
                                <p class="mb-2">Extension PHP <strong>ZipArchive</strong> tidak terinstall. File Excel (.xlsx, .xls) tidak dapat diproses.</p>
                                <p class="mb-2"><strong>Solusi:</strong></p>
                                <ol class="mb-0">
                                    <li><strong>Install Extension ZipArchive:</strong> Buka php.ini, uncomment <code>extension=zip</code>, restart server</li>
                                    <li><strong>Gunakan File CSV:</strong> Convert Excel ke CSV dan upload file CSV</li>
                                </ol>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Petunjuk Import:</h6>
                            <ol class="mb-0">
                                <li>Download template Excel terlebih dahulu</li>
                                <li>Isi data sesuai format kolom A-R (Kolom A diabaikan)</li>
                                <li>@if (!$zipArchiveAvailable) Convert ke CSV jika ZipArchive tidak tersedia @endif Upload file yang sudah diisi</li>
                                <li>System akan otomatis generate umur dari tanggal lahir</li>
                                <li>No BPJS akan terisi otomatis jika jenis pasien = BPJS</li>
                                <li>RS Rujukan akan terisi otomatis jika status = Rujuk</li>
                            </ol>
                        </div>

                        <form id="importForm" action="{{ route('diagnosa-pkm.import.proses') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="file" class="form-label">
                                    Pilih File 
                                    @if (!$zipArchiveAvailable)
                                        <span class="badge bg-warning text-dark">CSV Only</span>
                                    @else
                                        <span class="badge bg-success">Excel/CSV</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control" id="file" name="file" 
                                       accept="{{ $zipArchiveAvailable ? '.xlsx,.xls,.csv' : '.csv' }}" 
                                       required>
                                <small class="form-text text-muted">
                                    @if (!$zipArchiveAvailable)
                                        Format file harus .csv (Excel tidak didukung karena ZipArchive tidak tersedia)
                                    @else
                                        Format file: .xlsx, .xls, atau .csv
                                    @endif
                                </small>
                            </div>
                            
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary" id="importBtn">
                                    <i class="fas fa-upload"></i> Import Data
                                </button>
                                @if ($zipArchiveAvailable)
                                    <a href="{{ route('diagnosa-pkm.download.template') }}" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i> Download Template Excel
                                    </a>
                                @endif
                                <a href="/template_pkm/Template_Data_Diagnosa_PKM.csv" class="btn btn-info">
                                    <i class="fas fa-file-csv"></i> Download Template CSV
                                </a>
                                <a href="{{ route('diagnosa-pkm.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> Lihat Data
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Informasi Format -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-table"></i> Format Kolom Excel</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Kolom</th>
                                        <th>Keterangan</th>
                                        <th>Wajib</th>
                                        <th>Keterangan Khusus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>A</code></td>
                                        <td>-</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">Diabaikan</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>B</code></td>
                                        <td>Tanggal Kunjungan</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Format: dd/mm/yyyy</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>C</code></td>
                                        <td>Poli</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">-</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>D</code></td>
                                        <td>No. Rekam Medik</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Unique</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>E</code></td>
                                        <td>NIK</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">-</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>F</code></td>
                                        <td>Nama Pasien</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">-</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>G</code></td>
                                        <td>Alamat</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">-</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>H</code></td>
                                        <td>Tanggal Lahir</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Format: dd/mm/yyyy</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>I</code></td>
                                        <td>Umur</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">Auto generate</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>J</code></td>
                                        <td>Jenis Kelamin</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">L/P</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>K</code></td>
                                        <td>Jenis Pasien</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Umum/BPJS/dll</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>L</code></td>
                                        <td>No. BPJS</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">Terisi jika K=BPJS</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>M</code></td>
                                        <td>Jenis Bayar</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Tunai/Asuransi/dll</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>N</code></td>
                                        <td>Anamnesa</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">-</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>O</code></td>
                                        <td>Diagnosa</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Kode ICD-10 di awal</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>P</code></td>
                                        <td>Pemeriksa</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Nama dokter</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>Q</code></td>
                                        <td>Status</td>
                                        <td><span class="badge bg-danger">Ya</span></td>
                                        <td><small class="text-muted">Sehat/Rujuk/dll</small></td>
                                    </tr>
                                    <tr>
                                        <td><code>R</code></td>
                                        <td>RS Rujukan</td>
                                        <td><span class="badge bg-secondary">Tidak</span></td>
                                        <td><small class="text-muted">Terisi jika Q=Rujuk</small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).on('submit', '#importForm', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const importBtn = document.getElementById('importBtn');
                importBtn.disabled = true;

                let progress = 0;
                let uploadStarted = false;
                let uploadFinished = false;
                let uploadStartTime = null;

                Swal.fire({
                    title: 'Mengupload file...',
                    html: `
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="progressBar">0%</div>
                        </div>
                        <p class="mt-2" id="progressText">Sedang mengupload file...</p>
                    `,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();

                        setTimeout(() => {
                            uploadStarted = true;
                            uploadStartTime = Date.now();

                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', form.action, true);
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    progress = Math.round((e.loaded / e.total) * 100);
                                    document.getElementById('progressBar').style.width = progress + '%';
                                    document.getElementById('progressBar').textContent = progress + '%';
                                    document.getElementById('progressText').textContent = 'Sedang mengupload file... (' +
                                        progress + '%)';
                                }
                            });

                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    importBtn.disabled = false;
                                    uploadFinished = true;
                                    document.getElementById('progressBar').style.width = '100%';
                                    document.getElementById('progressBar').textContent = '100%';
                                    document.getElementById('progressText').textContent = 'Upload selesai, memproses data...';

                                    let minDelay = 1500;
                                    let elapsed = Date.now() - uploadStartTime;
                                    let wait = elapsed < minDelay ? minDelay - elapsed : 0;

                                    setTimeout(() => {
                                        if (xhr.status === 200) {
                                            const data = JSON.parse(xhr.responseText);
                                            if (data.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                }).then(() => {
                                                    window.location.href = "{{ route('diagnosa-pkm.index') }}";
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal!',
                                                    text: data.message
                                                });
                                            }
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: 'Terjadi kesalahan saat mengupload/import data'
                                            });
                                        }
                                    }, wait);
                                }
                            };

                            xhr.send(formData);
                        }, 200);
                    }
                });
            });
        </script>
    @endpush
@endsection
