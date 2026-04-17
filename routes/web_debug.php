<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-form', function() {
    return view('debug-form');
});

Route::post('/debug-submit', function(\Illuminate\Http\Request $request) {
    try {
        \Log::info('Form submitted with data:', $request->all());
        
        $validated = $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'poli' => 'required|string|max:255',
            'no_rekam_medik' => 'required|string|max:255',
            'nik' => 'nullable|string|digits:16',
            'nama_pasien' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'jenis_pasien' => 'required|in:BPJS,Umum',
            'jenis_bayar' => 'required|in:PBI,NONPBI,Tunai',
            'anamnesa' => 'required|string',
            'diagnosa' => 'required|string',
            'pemeriksa' => 'required|string|max:255',
            'status' => 'required|in:Dilayani,Dirujuk,Lain-Lain',
            'rs_rujukan' => 'required_if:status,Dirujuk|string|max:255'
        ]);
        
        \Log::info('Validation passed:', $validated);
        
        // Gabungkan kode diagnosa dengan deskripsi
        $diagnosaKode = $request->input('diagnosa_kode');
        $diagnosaDeskripsi = $validated['diagnosa'];
        
        if ($diagnosaKode && !str_contains($diagnosaDeskripsi, $diagnosaKode)) {
            $validated['diagnosa'] = $diagnosaKode . ' - ' . $diagnosaDeskripsi;
        }
        
        \Log::info('Final data to save:', $validated);
        
        $pasien = \App\Models\Pasien::create($validated);
        
        \Log::info('Pasien created with ID: ' . $pasien->id);
        
        return "Success! Pasien created with ID: " . $pasien->id . "<br>Total pasiens: " . \App\Models\Pasien::count();
        
    } catch (\Exception $e) {
        \Log::error('Error creating pasien: ' . $e->getMessage());
        return "Error: " . $e->getMessage() . "<br>Trace: " . $e->getTraceAsString();
    }
});
