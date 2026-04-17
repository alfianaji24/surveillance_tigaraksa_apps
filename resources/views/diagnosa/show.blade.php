@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-injured"></i> Detail Data Diagnosa PKM
                        </h5>
                        <div>
                            <a href="{{ route('diagnosa-pkm.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Data Pasien -->
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-user"></i> Data Pasien</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%"><strong>Nama Pasien</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->nama_pasien }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Rekam Medik</strong></td>
                                                <td>:</td>
                                                <td><code>{{ $diagnosaPKM->no_rekam_medik }}</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>NIK</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->nik ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Lahir</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->tanggal_lahir ? $diagnosaPKM->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Umur</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->umur_formatted }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin</strong></td>
                                                <td>:</td>
                                                <td>
                                                    <span class="badge bg-{{ $diagnosaPKM->jenis_kelamin == 'L' ? 'primary' : 'pink' }}">
                                                        {{ $diagnosaPKM->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Alamat</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->alamat }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Kunjungan -->
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-calendar-check"></i> Data Kunjungan</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%"><strong>Tanggal Kunjungan</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->tanggal_kunjungan->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Poli</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->poli }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Pasien</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->jenis_pasien }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. BPJS</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->no_bpjs ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Bayar</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->jenis_bayar }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status</strong></td>
                                                <td>:</td>
                                                <td>
                                                    <span class="badge bg-{{ $diagnosaPKM->status == 'Sehat' ? 'success' : ($diagnosaPKM->status == 'Rujuk' ? 'warning' : 'secondary') }}">
                                                        {{ $diagnosaPKM->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>RS Rujukan</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->rs_rujukan ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Data Diagnosa -->
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-stethoscope"></i> Data Diagnosa</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%"><strong>Diagnosa</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->diagnosa }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kode ICD-10</strong></td>
                                                <td>:</td>
                                                <td><code>{{ $diagnosaPKM->kode_icd_10 }}</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pemeriksa</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->pemeriksa }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Anamnesa -->
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-notes-medical"></i> Anamnesa</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%"><strong>Anamnesa</strong></td>
                                                <td>:</td>
                                                <td>{{ $diagnosaPKM->anamnesa ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Sistem -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Sistem</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Dibuat:</strong> {{ $diagnosaPKM->created_at->format('d/m/Y H:i:s') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Diupdate:</strong> {{ $diagnosaPKM->updated_at->format('d/m/Y H:i:s') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
