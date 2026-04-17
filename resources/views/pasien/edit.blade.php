@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Edit Data Pasien</h4>
                    <p class="text-muted mb-0">Ubah data kunjungan pasien</p>
                </div>
                <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Form Edit Data Pasien
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pasien.update', $pasien->hash_id) }}" id="pasienForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Informasi Kunjungan -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-calendar-check me-2"></i>Informasi Kunjungan
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
                                                   value="{{ $pasien->tanggal_kunjungan ? $pasien->tanggal_kunjungan->format('Y-m-d') : '' }}" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="poli" class="form-label">
                                                Poli <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="poli" name="poli" required>
                                                <option value="">Pilih Poli</option>
                                                <option value="Umum" {{ $pasien->poli == 'Umum' ? 'selected' : '' }}>Poli Umum</option>
                                                <option value="Gigi" {{ $pasien->poli == 'Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                                                <option value="KIA" {{ $pasien->poli == 'KIA' ? 'selected' : '' }}>Poli KIA</option>
                                                <option value="Anak" {{ $pasien->poli == 'Anak' ? 'selected' : '' }}>Poli Anak</option>
                                                <option value="Lansia" {{ $pasien->poli == 'Lansia' ? 'selected' : '' }}>Poli Lansia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="no_rekam_medik" class="form-label">
                                                No. Rekam Medik <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" 
                                                   id="no_rekam_medik" 
                                                   name="no_rekam_medik" 
                                                   value="{{ $pasien->no_rekam_medik }}" 
                                                   placeholder="RM-001" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Identitas Pasien -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-id-card me-2"></i>Data Identitas Pasien
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nik" class="form-label">
                                                NIK <small class="text-muted">(Opsional, 16 digit)</small>
                                            </label>
                                            <input type="text" class="form-control" 
                                                   id="nik" 
                                                   name="nik" 
                                                   value="{{ $pasien->nik }}" 
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
                                                   value="{{ $pasien->nama_pasien }}" 
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
                                                      required>{{ $pasien->alamat }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Demografi -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-users me-2"></i>Data Demografi
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tanggal_lahir" class="form-label">
                                                Tanggal Lahir <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" 
                                                   id="tanggal_lahir" 
                                                   name="tanggal_lahir" 
                                                   value="{{ $pasien->tanggal_lahir ? $pasien->tanggal_lahir->format('Y-m-d') : '' }}" 
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
                                                   value="{{ $pasien->umur }}" 
                                                   placeholder="17 Thn 7 Bln 15 Hr" 
                                                   readonly
                                                   required>
                                            <small class="text-muted">Umur dihitung otomatis dari tanggal lahir</small>
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
                                                           id="jenis_kelamin_l" name="jenis_kelamin" value="L" 
                                                           {{ $pasien->jenis_kelamin == 'L' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_kelamin_l">
                                                        Laki-laki
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_kelamin_p" name="jenis_kelamin" value="P" 
                                                           {{ $pasien->jenis_kelamin == 'P' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_kelamin_p">
                                                        Perempuan
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pembayaran -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-credit-card me-2"></i>Data Pembayaran
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Jenis Pasien <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_pasien_bpjs" name="jenis_pasien" value="BPJS" 
                                                           {{ $pasien->jenis_pasien == 'BPJS' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_pasien_bpjs">
                                                        BPJS
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_pasien_umum" name="jenis_pasien" value="Umum" 
                                                           {{ $pasien->jenis_pasien == 'Umum' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_pasien_umum">
                                                        Umum
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="no_bpjs" class="form-label">
                                                No BPJS
                                            </label>
                                            <input type="text" class="form-control" 
                                                   id="no_bpjs" 
                                                   name="no_bpjs" 
                                                   value="{{ $pasien->no_bpjs ?? '' }}" 
                                                   placeholder="Nomor BPJS"
                                                   maxlength="20">
                                            <small class="form-text text-muted">Isi jika jenis pasien BPJS</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Jenis Bayar <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_bayar_pbi" name="jenis_bayar" value="PBI" 
                                                           {{ $pasien->jenis_bayar == 'PBI' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_bayar_pbi">
                                                        PBI
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_bayar_nonpbi" name="jenis_bayar" value="NONPBI" 
                                                           {{ $pasien->jenis_bayar == 'NONPBI' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_bayar_nonpbi">
                                                        NONPBI
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           id="jenis_bayar_tunai" name="jenis_bayar" value="Tunai" 
                                                           {{ $pasien->jenis_bayar == 'Tunai' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jenis_bayar_tunai">
                                                        Tunai
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Medis -->
                            <div class="col-12 mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-stethoscope me-2"></i>Data Medis
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
                                                      required>{{ $pasien->anamnesa }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Diagnosa ICD-10 <span class="text-danger">*</span>
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
                                            
                                            <small class="text-muted">Search diagnosa ICD-10, lalu klik "Tambah" untuk menambahkan ke daftar</small>
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
                                                   value="{{ $pasien->pemeriksa }}" 
                                                   placeholder="Dr. John Doe" 
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
                                                <option value="Dilayani" {{ $pasien->status == 'Dilayani' ? 'selected' : '' }}>Dilayani</option>
                                                <option value="Dirujuk" {{ $pasien->status == 'Dirujuk' ? 'selected' : '' }}>Dirujuk</option>
                                                <option value="Lain-Lain" {{ $pasien->status == 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Status Aktif <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status_active" name="status_active" required>
                                                <option value="1" {{ $pasien->status_active ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$pasien->status_active ? 'selected' : '' }}>Non-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RS Rujukan (muncul jika status = Dirujuk) -->
                            <div class="col-12 mb-4" id="rs_rujukan_section" style="{{ $pasien->status == 'Dirujuk' ? 'display: block;' : 'display: none;' }}">
                                <h6 class="text-warning fw-bold mb-3">
                                    <i class="fas fa-hospital me-2"></i>Rumah Sakit Rujukan
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rs_rujukan" class="form-label">
                                                Nama RS Rujukan <span class="text-danger" {{ $pasien->status == 'Dirujuk' ? '' : 'style="display: none;"' }}>*</span>
                                            </label>
                                            <input type="text" class="form-control" 
                                                   id="rs_rujukan" 
                                                   name="rs_rujukan" 
                                                   value="{{ $pasien->rs_rujukan }}" 
                                                   placeholder="RSUD Contoh"
                                                   {{ $pasien->status == 'Dirujuk' ? 'required' : '' }}>
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
                                        <i class="fas fa-save me-2"></i>Update Data Pasien
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
    const noBpjsInput = document.getElementById('no_bpjs');
    const jenisPasienBpjs = document.getElementById('jenis_pasien_bpjs');
    const jenisPasienUmum = document.getElementById('jenis_pasien_umum');
    
    // Data untuk menyimpan diagnosa yang dipilih
    let selectedDiagnoses = [];
    let currentSuggestion = null;

    // Fungsi untuk mengatur visibility No BPJS
    function toggleNoBpjsField() {
        if (jenisPasienBpjs.checked) {
            noBpjsInput.closest('.col-md-4').style.display = 'block';
            noBpjsInput.setAttribute('required', 'required');
        } else {
            noBpjsInput.closest('.col-md-4').style.display = 'none';
            noBpjsInput.removeAttribute('required');
            noBpjsInput.value = '';
        }
    }

    // Event listener untuk jenis pasien
    jenisPasienBpjs.addEventListener('change', toggleNoBpjsField);
    jenisPasienUmum.addEventListener('change', toggleNoBpjsField);

    // Initialize visibility on page load
    toggleNoBpjsField();

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
        validatePasienForm();
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

    // Initialize existing diagnoses
    const existingDiagnoses = @json($pasien->icd10Codes->map(function($item) {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'display' => $item->display
        ];
    }));
    
    selectedDiagnoses = [...existingDiagnoses];

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
        console.log('addDiagnosa called with:', diagnosa);
        
        // Cek apakah sudah ada
        if (selectedDiagnoses.find(d => d.id === diagnosa.id)) {
            console.log('Diagnosis already exists, skipping');
            return;
        }

        console.log('Adding diagnosis to selectedDiagnoses');
        selectedDiagnoses.push(diagnosa);
        console.log('selectedDiagnoses after add:', selectedDiagnoses);
        
        updateDiagnosesList();
        updateHiddenInput();
        validatePasienForm();
    }

    // Fungsi untuk menghapus diagnosa
    function removeDiagnosa(id) {
        selectedDiagnoses = selectedDiagnoses.filter(d => d.id != id);
        updateDiagnosesList();
        updateHiddenInput();
        validatePasienForm();
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

    // Custom styling untuk suggestions
    const style = document.createElement('style');
    style.textContent = `
        .diagnosa-suggestion {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .diagnosa-suggestion:hover {
            background-color: #f8f9fa !important;
        }
        .diagnosa-suggestion:last-child {
            border-bottom: none !important;
        }
    `;
    document.head.appendChild(style);

    // Toggle RS Rujukan section
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Dirujuk') {
            rsRujukanSection.style.display = 'block';
            rsRujukanInput.required = true;
        } else {
            rsRujukanSection.style.display = 'none';
            rsRujukanInput.required = false;
            rsRujukanInput.value = '';
        }
        validatePasienForm();
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
    function validatePasienForm() {
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
    }

    // Add event listeners untuk semua field
    form.addEventListener('input', validatePasienForm);
    form.addEventListener('change', validatePasienForm);
    
    // Add specific event listeners for radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', validatePasienForm);
    });

    // Custom styling untuk suggestions
    const suggestionStyle = document.createElement('style');
    suggestionStyle.textContent = `
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
    document.head.appendChild(suggestionStyle);

    // Initialize diagnoses list
    updateDiagnosesList();

    // Initial validation setelah delay untuk memastikan semua elemen loaded
    setTimeout(validatePasienForm, 100);
});
</script>
@endsection
