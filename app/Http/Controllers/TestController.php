<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class TestController extends Controller
{
    public function testForm()
    {
        return view('test-form');
    }
    
    public function testSubmit(Request $request)
    {
        try {
            // Log semua input
            \Log::info('Test form submitted with data:', $request->all());
            
            // Validasi sederhana
            $validated = $request->validate([
                'nama_pasien' => 'required|string|max:255',
                'diagnosa' => 'required|string',
                'diagnosa_kode' => 'nullable|string'
            ]);
            
            // Log hasil validasi
            \Log::info('Validation passed:', $validated);
            
            // Gabungkan diagnosa
            $finalDiagnosa = $validated['diagnosa'];
            if (!empty($validated['diagnosa_kode'])) {
                $finalDiagnosa = $validated['diagnosa_kode'] . ' - ' . $finalDiagnosa;
            }
            
            // Buat pasien sederhana
            $pasien = new Pasien();
            $pasien->tanggal_kunjungan = now();
            $pasien->poli = 'Test';
            $pasien->no_rekam_medik = 'TEST' . time();
            $pasien->nama_pasien = $validated['nama_pasien'];
            $pasien->alamat = 'Test Address';
            $pasien->tanggal_lahir = '1990-01-01';
            $pasien->umur = '30 tahun';
            $pasien->jenis_kelamin = 'L';
            $pasien->jenis_pasien = 'Umum';
            $pasien->jenis_bayar = 'Tunai';
            $pasien->anamnesa = 'Test anamnesa';
            $pasien->diagnosa = $finalDiagnosa;
            $pasien->pemeriksa = 'Test Doctor';
            $pasien->status = 'Dilayani';
            $pasien->save();
            
            \Log::info('Pasien created successfully with ID: ' . $pasien->id);
            
            return "✅ Success! Pasien created with ID: " . $pasien->id . 
                   "<br>Diagnosa: " . $pasien->diagnosa .
                   "<br>Total pasiens: " . Pasien::count();
                   
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . $e->getMessage());
            return "❌ Validation Error: " . $e->getMessage();
        } catch (\Exception $e) {
            \Log::error('General error: ' . $e->getMessage());
            return "❌ Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine();
        }
    }
}
