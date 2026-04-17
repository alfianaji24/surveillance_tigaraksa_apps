<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pasien;

Route::get('/test-pasien', function() {
    try {
        $pasien = new Pasien();
        $pasien->tanggal_kunjungan = now();
        $pasien->poli = 'Umum';
        $pasien->no_rekam_medik = 'TEST001';
        $pasien->nama_pasien = 'Test Patient';
        $pasien->alamat = 'Test Address';
        $pasien->tanggal_lahir = '1990-01-01';
        $pasien->umur = '34 tahun';
        $pasien->jenis_kelamin = 'L';
        $pasien->jenis_pasien = 'Umum';
        $pasien->jenis_bayar = 'Tunai';
        $pasien->anamnesa = 'Test anamnesa';
        $pasien->diagnosa = 'A00 - Cholera';
        $pasien->pemeriksa = 'Test Doctor';
        $pasien->status = 'Dilayani';
        $pasien->save();
        
        return "Success! Pasien created with ID: " . $pasien->id . "<br>Total pasiens: " . Pasien::count();
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
