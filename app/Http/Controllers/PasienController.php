<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Icd10Code;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-pasien')->only(['index', 'show', 'importPage']);
        $this->middleware('permission:create-pasien')->only(['create', 'store', 'importPKM', 'importExcel']);
        $this->middleware('permission:update-pasien')->only(['edit', 'update']);
        $this->middleware('permission:delete-pasien')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pasien::with('icd10Codes', 'poli', 'diagnosaPkm');
        
        // Filter by status_active
        if ($request->has('status_active') && $request->status_active !== '') {
            $statusActive = $request->status_active === '1' ? true : false;
            $query->where('status_active', $statusActive);
        }
        
        // Filter by search term
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medik', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }
        
        // Filter by poli (support both poli_id and poli string for backward compatibility)
        if ($request->has('poli') && $request->poli !== '') {
            if (is_numeric($request->poli)) {
                $query->where('poli_id', $request->poli);
            } else {
                // Try to find poli by name first
                $poli = \App\Models\Poli::where('nama', 'like', "%{$request->poli}%")->first();
                if ($poli) {
                    $query->where('poli_id', $poli->id);
                } else {
                    // Fallback to old string-based filter
                    $query->where('poli', $request->poli);
                }
            }
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
                
        $pasiens = $query->with('icd10Codes')->orderBy('tanggal_kunjungan', 'desc')->paginate(10);
        $polis = \App\Models\Poli::active()->orderBy('nama')->get();
        
        return view('pasien.index', compact('pasiens', 'polis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $icd10s = ICD10Code::orderBy('code')->get();
        return view('pasien.create', compact('icd10s'));
    }

    /**
     * Show the import page for bulk data entry.
     */
    public function importPage()
    {
        return view('pasien.import');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check if request is JSON (for bulk import)
            if ($request->isJson() || $request->header('Content-Type') === 'application/json') {
                return $this->storeFromJson($request);
            }

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
            'no_bpjs' => 'nullable|string|max:20',
            'jenis_bayar' => 'required|in:PBI,NONPBI,Tunai',
            'anamnesa' => 'required|string',
            'icd10_codes' => 'required|string',
            'pemeriksa' => 'required|string|max:255',
            'status' => 'required|in:Dilayani,Dirujuk,Lain-Lain',
            'status_active' => 'required|boolean',
            'rs_rujukan' => 'required_if:status,Dirujuk|string|max:255|nullable'
        ]);

        // Create pasien without icd10_codes first
        $icd10CodesString = $validated['icd10_codes'];
        unset($validated['icd10_codes']);
        
        $pasien = Pasien::create($validated);
        
        // Process comma-separated ICD10 codes
        $icd10Ids = array_filter(explode(',', $icd10CodesString));
        if (!empty($icd10Ids)) {
            $pasien->icd10Codes()->attach($icd10Ids);
        }

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store patient from JSON data (for bulk import)
     */
    private function storeFromJson(Request $request)
    {
        $data = $request->all();
        
        $validated = [
            'tanggal_kunjungan' => $data['tanggal_kunjungan'] ?? date('Y-m-d'),
            'poli' => $data['poli'] ?? '',
            'no_rekam_medik' => $data['no_rekam_medik'] ?? '',
            'nik' => $data['nik'] ?? null,
            'nama_pasien' => $data['nama_pasien'] ?? '',
            'alamat' => $data['alamat'] ?? '',
            'no_hp' => $data['no_hp'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'umur' => $data['umur'] ?? '',
            'jenis_kelamin' => $data['jenis_kelamin'] ?? '',
            'jenis_pasien' => $data['jenis_pasien'] ?? '',
            'no_bpjs' => $data['no_bpjs'] ?? null,
            'jenis_bayar' => $data['jenis_bayar'] ?? '',
            'anamnesa' => $data['anamnesa'] ?? '',
            'pemeriksa' => $data['pemeriksa'] ?? '',
            'status' => $data['status'] ?? '',
            'status_active' => true,
            'rs_rujukan' => $data['rs_rujukan'] ?? null
        ];

        // Create pasien
        $pasien = Pasien::create($validated);
        
        // Process ICD10 codes
        if (isset($data['icd10_codes']) && is_array($data['icd10_codes'])) {
            $icd10Ids = array_map(function($icd10) {
                return $icd10['id'];
            }, $data['icd10_codes']);
            $pasien->icd10Codes()->attach($icd10Ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil disimpan',
            'data' => $pasien
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pasien = Pasien::findByHashIdOrFail($id)->load('icd10Codes');
        $icd10s = Icd10Code::orderBy('code')->get();
        return view('pasien.edit', compact('pasien', 'icd10s'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pasien = Pasien::findByHashIdOrFail($id);

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
            'no_bpjs' => 'nullable|string|max:20',
            'jenis_bayar' => 'required|in:PBI,NONPBI,Tunai',
            'anamnesa' => 'required|string',
            'icd10_codes' => 'required|string',
            'pemeriksa' => 'required|string|max:255',
            'status' => 'required|in:Dilayani,Dirujuk,Lain-Lain',
            'status_active' => 'required|boolean',
            'rs_rujukan' => 'required_if:status,Dirujuk|string|max:255|nullable'
        ]);

        // Update pasien without icd10_codes first
        $icd10CodesString = $validated['icd10_codes'];
        unset($validated['icd10_codes']);
        
        $pasien->update($validated);
        
        // Process comma-separated ICD10 codes and sync
        $icd10Ids = array_filter(explode(',', $icd10CodesString));
        $pasien->icd10Codes()->sync($icd10Ids);

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pasien = Pasien::findByHashIdOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus');
    }

    /**
     * Import data dari template PKM (CSV only - stable solution)
     */
    public function importExcel(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|mimes:csv|max:2048'
            ]);

            $file = $request->file('excel_file');
            $data = [];

            // Handle CSV files
            $csvData = array_map('str_getcsv', file($file->getPathname()));
            $headers = array_shift($csvData); // Remove header
            
            foreach ($csvData as $row) {
                if (count($row) >= 17) {
                    $rowData = $this->mapRowToData($row);
                    if (!empty($rowData['nama_pasien']) || !empty($rowData['no_rekam_medik'])) {
                        // Validate required fields
                        if (empty($rowData['nama_pasien'])) {
                            $rowData['_validation_error'] = 'Nama pasien wajib diisi';
                        }
                        if (empty($rowData['no_rekam_medik'])) {
                            $rowData['_validation_error'] = 'No rekam medik wajib diisi';
                        }
                        
                        $data[] = $rowData;
                    }
                }
            }

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data valid ditemukan dalam file CSV. Pastikan data diisi mulai baris ke-2 dan memiliki minimal 17 kolom.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil dibaca dari file CSV'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * Helper untuk mapping row Excel/CSV ke data array
     */
    private function mapRowToData($row)
    {
        // Handle both indexed array (from Excel) and associative array
        if (isset($row[0])) {
            // Indexed array from Excel/CSV
            return [
                'tanggal_kunjungan' => $row[1] ?? '', // Column B
                'poli' => $row[2] ?? '', // Column C
                'no_rekam_medik' => $row[3] ?? '', // Column D
                'nik' => $row[4] ?? '', // Column E
                'nama_pasien' => $row[5] ?? '', // Column F
                'alamat' => $row[6] ?? '', // Column G
                'no_hp' => '', // Column H is tanggal_lahir
                'tanggal_lahir' => $row[7] ?? '', // Column H
                'umur' => $row[8] ?? '', // Column I
                'jenis_kelamin' => $row[9] ?? '', // Column J
                'jenis_pasien' => $row[10] ?? '', // Column K
                'no_bpjs' => $row[11] ?? '', // Column L
                'jenis_bayar' => $row[12] ?? '', // Column M
                'anamnesa' => $row[13] ?? '', // Column N
                'diagnosa_icd10' => $row[14] ?? '', // Column O
                'pemeriksa' => $row[15] ?? '', // Column P
                'status' => $row[16] ?? '', // Column Q
                'rs_rujukan' => $row[17] ?? '' // Column R
            ];
        } else {
            // Associative array
            return [
                'tanggal_kunjungan' => $row['tanggal_kunjungan'] ?? '',
                'poli' => $row['poli'] ?? '',
                'no_rekam_medik' => $row['no_rekam_medik'] ?? '',
                'nik' => $row['nik'] ?? '',
                'nama_pasien' => $row['nama_pasien'] ?? '',
                'alamat' => $row['alamat'] ?? '',
                'no_hp' => '',
                'tanggal_lahir' => $row['tanggal_lahir'] ?? '',
                'umur' => $row['umur'] ?? '',
                'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                'jenis_pasien' => $row['jenis_pasien'] ?? '',
                'no_bpjs' => $row['no_bpjs'] ?? '',
                'jenis_bayar' => $row['jenis_bayar'] ?? '',
                'anamnesa' => $row['anamnesa'] ?? '',
                'diagnosa_icd10' => $row['diagnosa'] ?? '', // Note: Excel uses 'diagnosa' not 'diagnosa_icd10'
                'pemeriksa' => $row['pemeriksa'] ?? '',
                'status' => $row['status'] ?? '',
                'rs_rujukan' => $row['rs_rujukan'] ?? ''
            ];
        }
    }

    /**
     * Import data langsung ke database
     */
    public function importToDatabase(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            $file = $request->file('excel_file');
            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];

            // Read data from file
            $importResult = $this->importExcel($request);
            $importData = json_decode($importResult->getContent(), true);

            if (!$importData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $importData['message']
                ]);
            }

            // Process each row and save to database
            foreach ($importData['data'] as $index => $rowData) {
                try {
                    // Validate required fields
                    if (empty($rowData['nama_pasien']) || empty($rowData['no_rekam_medik'])) {
                        $skippedCount++;
                        $errors[] = "Baris " . ($index + 2) . ": Nama pasien dan No Rekam Medik wajib diisi";
                        continue;
                    }

                    // Allow duplicate No Rekam Medik for multiple visits
                    // No duplicate check - pasien bisa datang berkali-kali

                    // Process the data
                    $processedData = $this->processRowData($rowData);
                    
                    // Save to database
                    $pasien = Pasien::create($processedData);
                    
                    // Process ICD10 codes
                    if (!empty($rowData['diagnosa_icd10'])) {
                        $icd10Ids = $this->processICD10Codes($rowData['diagnosa_icd10']);
                        if (!empty($icd10Ids)) {
                            $pasien->icd10Codes()->attach($icd10Ids);
                        }
                    }

                    $importedCount++;

                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import selesai! {$importedCount} data berhasil disimpan, {$skippedCount} data dilewati.",
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process row data untuk disimpan ke database
     */
    private function processRowData($rowData)
    {
        // Hitung umur dari tanggal lahir
        $umur = '';
        if (!empty($rowData['tanggal_lahir'])) {
            $birthDate = Carbon::parse($rowData['tanggal_lahir']);
            $today = Carbon::now();
            $years = $today->diffInYears($birthDate);
            $months = $today->diffInMonths($birthDate) % 12;
            $days = $today->diffInDays($birthDate) % 30;
            
            $umur = ($years > 0 ? $years . ' Thn ' : '') . 
                   ($months > 0 ? $months . ' Bln ' : '') . 
                   ($days > 0 ? $days . ' Hr' : '');
            $umur = trim($umur);
        }

        return [
            'tanggal_kunjungan' => $rowData['tanggal_kunjungan'] ?: date('Y-m-d'),
            'poli' => $rowData['poli'] ?? '',
            'no_rekam_medik' => $rowData['no_rekam_medik'],
            'nik' => $rowData['nik'] ?? null,
            'nama_pasien' => $rowData['nama_pasien'],
            'alamat' => $rowData['alamat'] ?? '',
            'no_hp' => $rowData['no_hp'] ?? null,
            'tanggal_lahir' => $rowData['tanggal_lahir'] ?? null,
            'umur' => $umur,
            'jenis_kelamin' => strtoupper($rowData['jenis_kelamin']) ?? '',
            'jenis_pasien' => $rowData['jenis_pasien'] ?? '',
            'no_bpjs' => $rowData['no_bpjs'] ?? null,
            'jenis_bayar' => $rowData['jenis_bayar'] ?? '',
            'anamnesa' => $rowData['anamnesa'] ?? '',
            'pemeriksa' => $rowData['pemeriksa'] ?? '',
            'status' => $rowData['status'] ?? '',
            'status_active' => true,
            'rs_rujukan' => $rowData['rs_rujukan'] ?? null
        ];
    }

    /**
     * Process ICD10 codes dari string
     */
    private function processICD10Codes($diagnosaString)
    {
        $icd10Ids = [];
        if (!empty($diagnosaString)) {
            // Handle multiple ICD-10 codes separated by semicolon or comma
            $diagnosaParts = preg_split('/[;,]/', $diagnosaString);
            
            foreach ($diagnosaParts as $part) {
                $part = trim($part);
                
                // Extract ICD-10 code (first word that matches pattern like A00.0, B12, etc.)
                if (preg_match('/\b([A-Z]\d{2}(\.\d{1,2})?)\b/', $part, $matches)) {
                    $code = $matches[1];
                    
                    $icd10 = Icd10Code::where('code', $code)->first();
                    if ($icd10) {
                        $icd10Ids[] = $icd10->id;
                    }
                }
            }
        }
        
        // Remove duplicates
        $icd10Ids = array_unique($icd10Ids);
        
        return $icd10Ids;
    }

    /**
     * Blast data dari Excel ke form pasien
     */
    public function blastData(Request $request)
    {
        try {
            $data = $request->all();
            
            $processedData = [];
            foreach ($data as $index => $row) {
                // Hitung umur dari tanggal lahir
                $umur = '';
                if (!empty($row['tanggal_lahir'])) {
                    $birthDate = Carbon::parse($row['tanggal_lahir']);
                    $today = Carbon::now();
                    $years = $today->diffInYears($birthDate);
                    $months = $today->diffInMonths($birthDate) % 12;
                    $days = $today->diffInDays($birthDate) % 30;
                    
                    $umur = ($years > 0 ? $years . ' Thn ' : '') . 
                           ($months > 0 ? $months . ' Bln ' : '') . 
                           ($days > 0 ? $days . ' Hr' : '');
                    $umur = trim($umur);
                }

                // Process ICD10 codes
                $icd10Codes = [];
                if (!empty($row['diagnosa_icd10'])) {
                    $diagnosaCodes = explode(',', $row['diagnosa_icd10']);
                    foreach ($diagnosaCodes as $code) {
                        $code = trim(strtoupper($code));
                        $icd10 = Icd10Code::where('code', $code)->first();
                        if ($icd10) {
                            $icd10Codes[] = [
                                'id' => $icd10->id,
                                'code' => $icd10->code,
                                'display' => $icd10->display
                            ];
                        }
                    }
                }

                $processedData[] = [
                    'tanggal_kunjungan' => $row['tanggal_kunjungan'] ?? date('Y-m-d'),
                    'poli' => $row['poli'] ?? '',
                    'no_rekam_medik' => $row['no_rekam_medik'] ?? '',
                    'nik' => $row['nik'] ?? '',
                    'nama_pasien' => $row['nama_pasien'] ?? '',
                    'alamat' => $row['alamat'] ?? '',
                    'no_hp' => $row['no_hp'] ?? '',
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? '',
                    'umur' => $umur,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                    'jenis_pasien' => $row['jenis_pasien'] ?? '',
                    'no_bpjs' => $row['no_bpjs'] ?? '',
                    'jenis_bayar' => $row['jenis_bayar'] ?? '',
                    'anamnesa' => $row['anamnesa'] ?? '',
                    'icd10_codes' => $icd10Codes,
                    'pemeriksa' => $row['pemeriksa'] ?? '',
                    'status' => $row['status'] ?? '',
                    'rs_rujukan' => $row['rs_rujukan'] ?? ''
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $processedData,
                'message' => 'Data berhasil diproses untuk blast'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import data PKM langsung ke tabel pasiens
     */
    public function importPKM(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240' // 10MB max
        ]);

        // Increase memory limit for large files
        ini_set('memory_limit', '1G');
        set_time_limit(300); // 5 minutes

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Check if ZipArchive is available for Excel files
            if (in_array($extension, ['xlsx', 'xls']) && !class_exists('ZipArchive')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Extension PHP ZipArchive tidak terinstall. Silakan gunakan file CSV atau install extension ZipArchive terlebih dahulu.'
                ], 500);
            }
            
            // Read data from file using existing importExcel method
            $importResult = $this->importExcelFromPKM($file);
            
            if (!$importResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $importResult['message']
                ]);
            }

            // Process each row and save to pasiens table with memory optimization
            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];
            $chunkSize = 100; // Process in chunks to avoid memory issues
            $totalRows = count($importResult['data']);

            foreach ($importResult['data'] as $index => $rowData) {
                try {
                    // Validate required fields
                    if (empty($rowData['nama_pasien']) || empty($rowData['no_rekam_medik'])) {
                        $skippedCount++;
                        $errors[] = "Baris " . ($index + 2) . ": Nama pasien dan No Rekam Medik wajib diisi";
                        continue;
                    }

                    // Allow duplicate No Rekam Medik for multiple visits
                    // No duplicate check - pasien bisa datang berkali-kali

                    // Process the data for pasiens table
                    $processedData = $this->processPKMRowData($rowData);
                    
                    // Save to pasiens table
                    $pasien = Pasien::create($processedData);
                    
                    // Process ICD10 codes
                    if (!empty($rowData['diagnosa_icd10'])) {
                        $icd10Ids = $this->processICD10Codes($rowData['diagnosa_icd10']);
                        if (!empty($icd10Ids)) {
                            $pasien->icd10Codes()->attach($icd10Ids);
                        }
                    }

                    $importedCount++;

                    // Clear memory every chunk
                    if (($index + 1) % $chunkSize === 0) {
                        gc_collect_cycles();
                    }

                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import selesai! {$importedCount} data pasien berhasil disimpan, {$skippedCount} data dilewati.",
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import Excel from PKM file (helper method with memory optimization)
     */
    private function importExcelFromPKM($file)
    {
        try {
            $data = [];
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->getPathname();
            $fileSize = $file->getSize();

            // Check file size (limit to 10MB)
            if ($fileSize > 10 * 1024 * 1024) {
                return [
                    'success' => false,
                    'message' => 'File terlalu besar. Maksimal ukuran file adalah 10MB.'
                ];
            }

            // Handle CSV files
            if ($extension === 'csv') {
                $handle = fopen($filePath, 'r');
                if (!$handle) {
                    return [
                        'success' => false,
                        'message' => 'Tidak dapat membuka file CSV.'
                    ];
                }

                // Read header
                $headers = fgetcsv($handle);
                if (!$headers) {
                    fclose($handle);
                    return [
                        'success' => false,
                        'message' => 'File CSV tidak valid atau kosong.'
                    ];
                }

                // Read data row by row to save memory
                $rowIndex = 0;
                while (($row = fgetcsv($handle)) !== false) {
                    $rowIndex++;
                    
                    if (count($row) >= 1) {
                        $rowData = $this->mapRowToData($row);
                        if (!empty($rowData['nama_pasien']) || !empty($rowData['no_rekam_medik'])) {
                            $data[] = $rowData;
                        }
                    }

                    // Clear memory every 100 rows
                    if ($rowIndex % 100 === 0) {
                        gc_collect_cycles();
                    }
                }
                fclose($handle);

            } else {
                // Handle Excel files with memory optimization
                $data = $this->readExcelData($file);
            }

            if (empty($data)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada data valid ditemukan dalam file. Pastikan file memiliki format yang benar dan minimal 17 kolom.'
                ];
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Data berhasil dibaca dari file (' . count($data) . ' baris)'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error membaca file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process PKM row data untuk pasiens table
     */
    private function processPKMRowData($rowData)
    {
        // Gunakan umur dari Excel jika ada, jika tidak hitung dari tanggal lahir
        $umur = '';
        if (!empty($rowData['umur'])) {
            // Gunakan umur langsung dari Excel
            $umur = $rowData['umur'];
        } elseif (!empty($rowData['tanggal_lahir'])) {
            // Hitung umur dari tanggal lahir
            $birthDate = Carbon::parse($rowData['tanggal_lahir']);
            $today = Carbon::now();
            $years = $today->diffInYears($birthDate);
            $months = $today->diffInMonths($birthDate) % 12;
            $days = $today->diffInDays($birthDate) % 30;
            
            $umur = ($years > 0 ? $years . ' Thn ' : '') . 
                   ($months > 0 ? $months . ' Bln ' : '') . 
                   ($days > 0 ? $days . ' Hr' : '');
            $umur = trim($umur);
        }

        return [
            'tanggal_kunjungan' => $rowData['tanggal_kunjungan'] ?: date('Y-m-d'),
            'poli' => $rowData['poli'] ?? '',
            'no_rekam_medik' => $rowData['no_rekam_medik'],
            'nik' => $rowData['nik'] ?? null,
            'nama_pasien' => $rowData['nama_pasien'],
            'alamat' => $rowData['alamat'] ?? '',
            'no_hp' => $rowData['no_hp'] ?? null,
            'tanggal_lahir' => $rowData['tanggal_lahir'] ?? null,
            'umur' => $umur,
            'jenis_kelamin' => strtoupper($rowData['jenis_kelamin']) ?? '',
            'jenis_pasien' => ucfirst($rowData['jenis_pasien']) ?? '', // Normalize BPJS to bpjs
            'no_bpjs' => $rowData['no_bpjs'] ?? null, // Include 00000000000 if present in Excel
            'jenis_bayar' => $rowData['jenis_bayar'] ?? '',
            'anamnesa' => $rowData['anamnesa'] ?? '',
            'diagnosa' => $rowData['diagnosa_icd10'] ?? '', // Save diagnosa text to database
            'pemeriksa' => $rowData['pemeriksa'] ?? '',
            'status' => $rowData['status'] ?? 'Dilayani',
            'status_active' => true,
            'rs_rujukan' => $rowData['rs_rujukan'] ?? null
        ];
    }

    /**
     * Read Excel data using Laravel Excel
     */
    private function readExcelData($file)
    {
        $tempData = [];
        
        try {
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                $csvData = array_map('str_getcsv', file($file->getPathname()));
                $headers = array_shift($csvData);
                
                foreach ($csvData as $row) {
                    if (count($row) >= 17) {
                        $rowData = $this->mapRowToData($row);
                        if (!empty($rowData['nama_pasien']) || !empty($rowData['no_rekam_medik'])) {
                            $tempData[] = $rowData;
                        }
                    }
                }
            } else {
                // For Excel files, we'll use a simple approach
                // Convert Excel to CSV temporarily
                $tempCsvPath = sys_get_temp_dir() . '/temp_import_' . time() . '.csv';
                
                try {
                    // Load Excel and convert to array
                    $dataArray = Excel::toArray([], $file->getPathname());
                    
                    if (!empty($dataArray) && !empty($dataArray[0])) {
                        $headers = array_shift($dataArray[0]); // Remove header row
                        
                        foreach ($dataArray[0] as $row) {
                            if (count($row) >= 17) {
                                $rowData = $this->mapRowToData($row);
                                if (!empty($rowData['nama_pasien']) || !empty($rowData['no_rekam_medik'])) {
                                    $tempData[] = $rowData;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    throw new \Exception('Failed to read Excel file: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to read file data: ' . $e->getMessage());
        }
        
        return $tempData;
    }

    /**
     * Download template PKM
     */
    public function downloadTemplatePKM()
    {
        $templatePath = public_path('template_upload_form/form_pkm.xlsx');
        
        if (!file_exists($templatePath)) {
            abort(404, 'Template tidak ditemukan');
        }

        return response()->download($templatePath, 'form_pkm.xlsx');
    }
}
