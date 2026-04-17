@extends('layouts.app')

@section('title', 'Tambah Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Tambah Data Pasien</h4>
                    <p class="text-muted mb-0">Input data kunjungan pasien baru</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('pasien.store') }}" id="pasienForm">
                        @csrf
                        <div class="row">
                            <!-- 1. Grup Kunjungan -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">
                                            <i class="fas fa-calendar-check me-2"></i>1. Kunjungan
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="tanggal_kunjungan" class="form-label">
                                                        Tanggal Kunjungan <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date" class="form-control" 
                                                           id="tanggal_kunjungan" 
                                                           name="tanggal_kunjungan" 
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="poli" class="form-label">
                                                        Pilih Poli <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" id="poli" name="poli" required>
                                                        <option value="">Pilih Poli</option>
                                                        <option value="Umum">Poli Umum</option>
                                                        <option value="Gigi">Poli Gigi</option>
                                                        <option value="KIA">Poli KIA</option>
                                                        <option value="Anak">Poli Anak</option>
                                                        <option value="Lansia">Poli Lansia</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="no_rekam_medik" class="form-label">
                                                        No Rekam Medik <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="no_rekam_medik" 
                                                           name="no_rekam_medik" 
                                                           placeholder="RM-001" 
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Data Identitas Pasien -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">
                                            <i class="fas fa-id-card me-2"></i>2. Data Identitas Pasien
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nik" class="form-label">
                                                        NIK <small class="text-muted">(Opsional)</small>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="nik" 
                                                           name="nik" 
                                                           placeholder="1234567890123456" 
                                                           maxlength="16"
                                                           pattern="[0-9]{16}"
                                                           title="NIK harus 16 digit angka">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nama_pasien" class="form-label">
                                                        Nama Pasien <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="nama_pasien" 
                                                           name="nama_pasien" 
                                                           placeholder="John Doe" 
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="alamat" class="form-label">
                                                        Alamat <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control" 
                                                              id="alamat" 
                                                              name="alamat" 
                                                              rows="2" 
                                                              placeholder="Jl. Contoh No. 123, RT/RW 001/002, Kelurahan, Kecamatan, Kabupaten/Kota" 
                                                              required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="no_hp" class="form-label">
                                                        No HP <small class="text-muted">(Opsional)</small>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="no_hp" 
                                                           name="no_hp" 
                                                           placeholder="08123456789" 
                                                           maxlength="14"
                                                           pattern="08[0-9]{9,12}"
                                                           title="No. HP harus diawali dengan 08 dan minimal 11 digit">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="tanggal_lahir" class="form-label">
                                                        Tanggal Lahir <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date" class="form-control" 
                                                           id="tanggal_lahir" 
                                                           name="tanggal_lahir" 
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="umur" class="form-label">
                                                        Umur <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="umur" 
                                                           name="umur" 
                                                           placeholder="Dihitung otomatis" 
                                                           readonly
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Jenis Kelamin <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_kelamin_l" name="jenis_kelamin" value="L" required>
                                                            <label class="form-check-label" for="jenis_kelamin_l">
                                                                Laki-laki
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_kelamin_p" name="jenis_kelamin" value="P" required>
                                                            <label class="form-check-label" for="jenis_kelamin_p">
                                                                Perempuan
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Data Pembayaran -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">
                                            <i class="fas fa-credit-card me-2"></i>3. Data Pembayaran
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Jenis Pasien <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_pasien_bpjs" name="jenis_pasien" value="BPJS" required>
                                                            <label class="form-check-label" for="jenis_pasien_bpjs">
                                                                BPJS
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_pasien_umum" name="jenis_pasien" value="Umum" required>
                                                            <label class="form-check-label" for="jenis_pasien_umum">
                                                                Umum
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- No BPJS (muncul jika jenis pasien = BPJS) -->
                                            <div class="col-md-6" id="bpjs_field" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="no_bpjs" class="form-label">
                                                        No BPJS <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="no_bpjs" 
                                                           name="no_bpjs" 
                                                           placeholder="0001234567890" 
                                                           maxlength="20"
                                                           pattern="[0-9]{13,20}"
                                                           title="No. BPJS harus 13-20 digit angka">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Jenis Bayar <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_bayar_pbi" name="jenis_bayar" value="PBI" required>
                                                            <label class="form-check-label" for="jenis_bayar_pbi">
                                                                PBI
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_bayar_nonpbi" name="jenis_bayar" value="NONPBI" required>
                                                            <label class="form-check-label" for="jenis_bayar_nonpbi">
                                                                NONPBI
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   id="jenis_bayar_tunai" name="jenis_bayar" value="Tunai" required>
                                                            <label class="form-check-label" for="jenis_bayar_tunai">
                                                                Tunai
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Data Medis -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">
                                            <i class="fas fa-stethoscope me-2"></i>4. Data Medis
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="anamnesa" class="form-label">
                                                        Anamnesa <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control" 
                                                              id="anamnesa" 
                                                              name="anamnesa" 
                                                              rows="3" 
                                                              placeholder="Keluhan pasien..." 
                                                              required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Diagnosa ICD10 <span class="text-danger">*</span>
                                                    </label>
                                                    
                                                    <!-- Search Input -->
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="icd10_search" 
                                                               placeholder="Ketik kode atau nama diagnosa (contoh: A00 atau Kolera)" 
                                                               autocomplete="off">
                                                        <button type="button" 
                                                                class="btn btn-primary" 
                                                                id="add_diagnosa_btn">
                                                            <i class="fas fa-plus"></i> Tambah
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Suggestions Dropdown -->
                                                    <div id="icd10_suggestions" 
                                                         class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" 
                                                         style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
                                                    </div>
                                                    
                                                    <!-- Selected Diagnoses -->
                                                    <div id="selected_diagnoses" class="mt-2">
                                                        <div class="d-flex flex-wrap gap-2" id="diagnoses_list">
                                                            <!-- Selected diagnoses will appear here -->
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Hidden Input for Form Submission -->
                                                    <input type="hidden" id="icd10_codes_input" name="icd10_codes" value="">
                                                    
                                                    <small class="text-muted">Search diagnosa ICD-10, lalu klik "Tambah" untuk menambahkan ke daftar. Bisa dihapus dengan klik tombol ✕</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="pemeriksa" class="form-label">
                                                        Pemeriksa <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="pemeriksa" 
                                                           name="pemeriksa" 
                                                           placeholder="Dr. John" 
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Status <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="Dilayani">Dilayani</option>
                                                        <option value="Dirujuk">Dirujuk</option>
                                                        <option value="Lain-Lain">Lain-Lain</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- Rumah Sakit Rujukan (muncul jika status = Dirujuk) -->
                                            <div class="col-md-12" id="rs_rujukan_field" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="rs_rujukan" class="form-label">
                                                        Rumah Sakit Dituju <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" 
                                                           id="rs_rujukan" 
                                                           name="rs_rujukan" 
                                                           placeholder="Nama Rumah Sakit">
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="status_active" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-save me-2"></i>Simpan Data Pasien
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pasienForm');
    const submitBtn = document.getElementById('submitBtn');
    const statusSelect = document.getElementById('status');
    const rsRujukanSection = document.getElementById('rs_rujukan_section');
    const rsRujukanInput = document.getElementById('rs_rujukan');
    const nikInput = document.getElementById('nik');
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    const umurInput = document.getElementById('umur');
    const icd10SearchInput = document.getElementById('icd10_search');
    const icd10Suggestions = document.getElementById('icd10_suggestions');
    const addDiagnosaBtn = document.getElementById('add_diagnosa_btn');
    const diagnosesList = document.getElementById('diagnoses_list');
    const icd10CodesInput = document.getElementById('icd10_codes_input');
    
    // Data untuk menyimpan diagnosa yang dipilih
    let selectedDiagnoses = [];
    let currentSuggestion = null;

    // Load existing diagnoses if in edit mode
    @if(isset($pasien) && $pasien->icd10Codes)
        const existingDiagnoses = @json($pasien->icd10Codes->map(function($icd10) {
            return [
                'id' => $icd10->id,
                'code' => $icd10->code,
                'display' => $icd10->display
            ];
        }));
        selectedDiagnoses = existingDiagnoses;
        updateDiagnosesList();
        updateHiddenInput();
    @endif

    // Fungsi untuk menghitung umur
    function hitungUmur(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);
        
        let tahun = today.getFullYear() - birthDate.getFullYear();
        let bulan = today.getMonth() - birthDate.getMonth();
        let hari = today.getDate() - birthDate.getDate();
        
        // Adjust jika hari negatif
        if (hari < 0) {
            bulan--;
            const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            hari += lastMonth.getDate();
        }
        
        // Adjust jika bulan negatif
        if (bulan < 0) {
            tahun--;
            bulan += 12;
        }
        
        // Format output
        let umurText = '';
        if (tahun > 0) {
            umurText += tahun + ' Thn ';
        }
        if (bulan > 0) {
            umurText += bulan + ' Bln ';
        }
        if (hari > 0 || (tahun === 0 && bulan === 0)) {
            umurText += hari + ' Hr';
        }
        
        return umurText.trim();
    }

    // Event listener untuk perubahan tanggal lahir
    tanggalLahirInput.addEventListener('change', function() {
        if (this.value) {
            const umur = hitungUmur(this.value);
            umurInput.value = umur;
            umurInput.setCustomValidity('');
        } else {
            umurInput.value = '';
            umurInput.setCustomValidity('Tanggal lahir harus diisi');
        }
        validateForm();
    });

    // Hitung umur saat halaman dimuat jika tanggal lahir sudah ada
    if (tanggalLahirInput.value) {
        umurInput.value = hitungUmur(tanggalLahirInput.value);
    }

    // Data ICD-10 untuk autocomplete
    const icd10Data = @json($icd10s->map(function($item) {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'display' => $item->display
        ];
    }));

    // Fungsi untuk menampilkan suggestions
    function showSuggestions(searchTerm) {
        if (searchTerm.length < 2) {
            icd10Suggestions.style.display = 'none';
            currentSuggestion = null;
            return;
        }

        const filtered = icd10Data.filter(item => 
            item.code.toLowerCase().includes(searchTerm.toLowerCase()) || 
            item.display.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filtered.length === 0) {
            icd10Suggestions.style.display = 'none';
            currentSuggestion = null;
            return;
        }

        let html = '';
        filtered.slice(0, 10).forEach(item => {
            html += `
                <div class="suggestion-item px-3 py-2 border-bottom cursor-pointer hover:bg-light" 
                     data-id="${item.id}" 
                     data-code="${item.code}" 
                     data-display="${item.display}">
                    <strong>${item.code}</strong> - ${item.display}
                </div>
            `;
        });

        icd10Suggestions.innerHTML = html;
        icd10Suggestions.style.display = 'block';
    }

    // Event listener untuk search input
    icd10SearchInput.addEventListener('input', function() {
        showSuggestions(this.value);
    });

    // Event listener untuk klik pada suggestion
    icd10Suggestions.addEventListener('click', function(e) {
        const suggestion = e.target.closest('.suggestion-item');
        if (suggestion) {
            const id = suggestion.dataset.id;
            const code = suggestion.dataset.code;
            const display = suggestion.dataset.display;
            
            icd10SearchInput.value = `${code} - ${display}`;
            currentSuggestion = { id, code, display };
            icd10Suggestions.style.display = 'none';
        }
    });

    // Event listener untuk tombol tambah
    addDiagnosaBtn.addEventListener('click', function() {
        if (currentSuggestion) {
            addDiagnosa(currentSuggestion);
            icd10SearchInput.value = '';
            currentSuggestion = null;
        } else if (icd10SearchInput.value.trim()) {
            // Cari berdasarkan input text
            const searchTerm = icd10SearchInput.value.trim();
            const found = icd10Data.find(item => 
                item.code.toLowerCase() === searchTerm.toLowerCase() ||
                item.display.toLowerCase().includes(searchTerm.toLowerCase()) ||
                `${item.code} - ${item.display}`.toLowerCase() === searchTerm.toLowerCase()
            );
            
            if (found) {
                addDiagnosa(found);
                icd10SearchInput.value = '';
            }
        }
    });

    // Event listener untuk Enter key
    icd10SearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addDiagnosaBtn.click();
        }
    });

    // Fungsi untuk menambah diagnosa
    function addDiagnosa(diagnosa) {
        // Cek apakah sudah ada
        if (selectedDiagnoses.find(d => d.id === diagnosa.id)) {
            return;
        }

        selectedDiagnoses.push(diagnosa);
        updateDiagnosesList();
        updateHiddenInput();
        validateForm();
    }

    // Fungsi untuk menghapus diagnosa
    function removeDiagnosa(id) {
        console.log('Removing diagnosa with ID:', id);
        console.log('Before removal:', selectedDiagnoses);
        
        selectedDiagnoses = selectedDiagnoses.filter(d => d.id != id);
        
        console.log('After removal:', selectedDiagnoses);
        
        updateDiagnosesList();
        updateHiddenInput();
        validateForm();
    }

    // Fungsi untuk update tampilan daftar diagnosa
    function updateDiagnosesList() {
        if (selectedDiagnoses.length === 0) {
            diagnosesList.innerHTML = '<span class="text-muted">Belum ada diagnosa yang dipilih</span>';
            return;
        }

        let html = '';
        selectedDiagnoses.forEach(diagnosa => {
            html += `
                <span class="badge bg-info d-inline-flex align-items-center me-1 mb-1 p-2" style="font-size: 0.9em; position: relative;">
                    ${diagnosa.code}
                    <span class="ms-2 remove-diagnosa-btn" 
                          style="cursor: pointer; font-weight: bold; margin-left: 8px;" 
                          data-id="${diagnosa.id}" 
                          title="Hapus ${diagnosa.code}">
                        ✕
                    </span>
                </span>
            `;
        });
        diagnosesList.innerHTML = html;
        
        // Add event listeners untuk tombol hapus
        document.querySelectorAll('.remove-diagnosa-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                removeDiagnosa(id);
            });
        });
    }

    // Fungsi untuk update hidden input
    function updateHiddenInput() {
        const ids = selectedDiagnoses.map(d => d.id);
        icd10CodesInput.value = ids.join(',');
    }

    // Hide suggestions saat klik di luar
    document.addEventListener('click', function(e) {
        if (!icd10SearchInput.contains(e.target) && !icd10Suggestions.contains(e.target)) {
            icd10Suggestions.style.display = 'none';
        }
    });

    
    
    // NIK validation
    nikInput.addEventListener('input', function() {
        const value = this.value.replace(/\D/g, ''); // Remove non-digits
        this.value = value;
        
        if (value.length > 0 && value.length !== 16) {
            this.setCustomValidity('NIK harus 16 digit');
        } else {
            this.setCustomValidity('');
        }
    });

    // Form validation untuk enable/disable submit button
    function validateForm() {
        let isValid = true;
        
        // Check individual required fields
        const requiredInputs = form.querySelectorAll('input[required]:not([type="radio"]), textarea[required]');
        requiredInputs.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });

        // Check ICD10 selected diagnoses
        if (selectedDiagnoses.length === 0) {
            isValid = false;
        }

        // Check radio button groups
        const radioGroups = ['jenis_kelamin', 'jenis_pasien', 'jenis_bayar'];
        radioGroups.forEach(name => {
            const checkedRadio = form.querySelector(`input[name="${name}"]:checked`);
            if (!checkedRadio) {
                isValid = false;
            }
        });

        // Check if RS Rujukan required and filled
        if (statusSelect.value === 'Dirujuk' && !rsRujukanInput.value.trim()) {
            isValid = false;
        }

        submitBtn.disabled = !isValid;
        return isValid;
    }

    // Add event listeners untuk semua field
    form.addEventListener('input', validateForm);
    form.addEventListener('change', validateForm);
    
    // Add specific event listeners for radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', validateForm);
    });

    // Event listener untuk jenis pasien (show/hide BPJS field)
    document.querySelectorAll('input[name="jenis_pasien"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const bpjsField = document.getElementById('bpjs_field');
            const noBpjsInput = document.getElementById('no_bpjs');
            
            if (this.value === 'BPJS') {
                bpjsField.style.display = 'block';
                noBpjsInput.required = true;
            } else {
                bpjsField.style.display = 'none';
                noBpjsInput.required = false;
                noBpjsInput.value = '';
            }
            validateForm();
        });
    });

    // Event listener untuk status (show/hide RS Rujukan)
    statusSelect.addEventListener('change', function() {
        const rsRujukanField = document.getElementById('rs_rujukan_field');
        const rsRujukanInput = document.getElementById('rs_rujukan');
        
        if (this.value === 'Dirujuk') {
            rsRujukanField.style.display = 'block';
            rsRujukanInput.required = true;
        } else {
            rsRujukanField.style.display = 'none';
            rsRujukanInput.required = false;
            rsRujukanInput.value = '';
        }
        validateForm();
    });

    // Custom styling untuk suggestions
    const style = document.createElement('style');
    style.textContent = `
        .suggestion-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .suggestion-item:hover {
            background-color: #f8f9fa !important;
        }
        .suggestion-item:last-child {
            border-bottom: none !important;
        }
    `;
    document.head.appendChild(style);

    // Initialize diagnoses list
    updateDiagnosesList();

    // Initial validation setelah delay untuk memastikan semua elemen loaded
    setTimeout(validateForm, 100);
});
</script>

<script>
    // Load blast data if available from import page
    const blastData = sessionStorage.getItem('blastData');
    if (blastData) {
        try {
            const data = JSON.parse(blastData);
            fillFormWithData(data);
            sessionStorage.removeItem('blastData'); // Clear after use
        } catch (error) {
            console.error('Error loading blast data:', error);
        }
    }

    function fillFormWithData(data) {
        // Fill form fields
        document.getElementById('tanggal_kunjungan').value = data.tanggal_kunjungan || '';
        document.getElementById('poli').value = data.poli || '';
        document.getElementById('no_rekam_medik').value = data.no_rekam_medik || '';
        document.getElementById('nik').value = data.nik || '';
        document.getElementById('nama_pasien').value = data.nama_pasien || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('no_hp').value = data.no_hp || '';
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
        document.getElementById('umur').value = data.umur || '';
        
        // Set radio buttons
        if (data.jenis_kelamin) {
            document.querySelector(`input[name="jenis_kelamin"][value="${data.jenis_kelamin}"]`).checked = true;
        }
        
        if (data.jenis_pasien) {
            document.querySelector(`input[name="jenis_pasien"][value="${data.jenis_pasien}"]`).checked = true;
            // Trigger change for BPJS field
            document.querySelector(`input[name="jenis_pasien"][value="${data.jenis_pasien}"]`).dispatchEvent(new Event('change'));
        }
        
        if (data.jenis_bayar) {
            document.querySelector(`input[name="jenis_bayar"][value="${data.jenis_bayar}"]`).checked = true;
        }
        
        document.getElementById('no_bpjs').value = data.no_bpjs || '';
        document.getElementById('anamnesa').value = data.anamnesa || '';
        document.getElementById('pemeriksa').value = data.pemeriksa || '';
        document.getElementById('status').value = data.status || '';
        
        // Trigger change for RS Rujukan field
        document.getElementById('status').dispatchEvent(new Event('change'));
        document.getElementById('rs_rujukan').value = data.rs_rujukan || '';
        
        // Fill ICD10 codes
        if (data.icd10_codes && data.icd10_codes.length > 0) {
            selectedDiagnoses = data.icd10_codes;
            updateDiagnosesList();
            updateHiddenInput();
        }
        
        // Trigger form validation
        validateForm();
    }
});
</script>
@endsection
