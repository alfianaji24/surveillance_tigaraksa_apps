<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PenyakitData;

class PenyakitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-penyakit')->only(['index', 'show']);
        $this->middleware('permission:create-penyakit')->only(['create', 'store']);
        $this->middleware('permission:update-penyakit')->only(['edit', 'update']);
        $this->middleware('permission:delete-penyakit')->only(['destroy']);
        $this->middleware('permission:import-penyakit')->only(['import', 'template']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $minggu = $request->get('minggu');
        
        // Get data based on filters
        if ($minggu) {
            // Filter by specific week
            $penyakitData = PenyakitData::getByYearAndWeek($tahun, $minggu);
            $weeklyData = [];
        } else {
            // Get top 10 for the year
            $penyakitData = PenyakitData::getTop10ByYear($tahun);
            $weeklyData = PenyakitData::getWeeklyDataByYear($tahun);
        }
        
        return view('penyakit.index', compact('penyakitData', 'weeklyData', 'tahun', 'minggu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penyakit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('penyakit.index')->with('success', 'Data penyakit berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // For now, just return the show view with basic data
        // TODO: Implement actual data retrieval based on $id
        return view('penyakit.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('penyakit.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update logic
        return redirect()->route('penyakit.index')->with('success', 'Data penyakit berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement delete logic
        return redirect()->route('penyakit.index')->with('success', 'Data penyakit berhasil dihapus');
    }

    /**
     * Export data penyakit
     */
    public function export()
    {
        // TODO: Implement export logic
        return response()->download('penyakit.xlsx');
    }

    /**
     * Download template Excel
     */
    public function template()
    {
        $filename = 'template_10_besar_penyakit.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        // Get current week info
        $currentWeek = date('W');
        $currentDate = now()->format('d M Y');
        
        // Create dynamic week headers
        $weekHeaders = ['No', 'Nama Penyakit'];
        for ($i = 1; $i <= 52; $i++) {
            $weekHeaders[] = 'M' . $i;
        }
        
        // Create template data
        $templateData = [$weekHeaders];
        
        $penyakitList = [
            '1' => 'Demam Berdarah',
            '2' => 'Tifus', 
            '3' => 'Diare',
            '4' => 'ISPA',
            '5' => 'Hepatitis',
            '6' => 'TBC',
            '7' => 'Malaria',
            '8' => 'Cacar Air',
            '9' => 'Campak',
            '10' => 'Leptospirosis'
        ];
        
        foreach ($penyakitList as $no => $nama) {
            $row = [$no, $nama];
            for ($i = 1; $i <= 52; $i++) {
                // Highlight current week with example data
                if ($i == $currentWeek) {
                    $row[] = '5'; // Example data for current week
                } else {
                    $row[] = '0';
                }
            }
            $templateData[] = $row;
        }
        
        // Create CSV content
        $csvContent = '';
        foreach ($templateData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }
        
        // Add info at the end
        $csvContent .= "\n# Template 10 Besar Penyakit\n";
        $csvContent .= "# Generated: " . $currentDate . "\n";
        $csvContent .= "# Current Week: M" . $currentWeek . "\n";
        $csvContent .= "# Format: No,Nama Penyakit,M1,M2,...,M52\n";
        $csvContent .= "# M1 = Minggu ke-1, M2 = Minggu ke-2, dst\n";
        
        return response($csvContent)
            ->withHeaders($headers)
            ->setStatusCode(200);
    }

    /**
     * Import data penyakit
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:5120',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        try {
            $file = $request->file('excel_file');
            $tahun = $request->tahun;
            
            // Read CSV file
            $csvData = file_get_contents($file);
            $lines = explode("\n", $csvData);
            
            $importedCount = 0;
            $penyakitList = [
                'Demam Berdarah', 'Tifus', 'Diare', 'ISPA', 'Hepatitis',
                'TBC', 'Malaria', 'Cacar Air', 'Campak', 'Leptospirosis'
            ];
            
            // Skip header line and process data
            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue; // Skip empty lines and comments
                }
                
                $data = str_getcsv($line);
                if (count($data) < 3) {
                    continue; // Skip invalid lines
                }
                
                $no = $data[0];
                $namaPenyakit = $data[1];
                
                // Check if this is a valid disease
                if (!in_array($namaPenyakit, $penyakitList)) {
                    continue;
                }
                
                // Process weekly data (M1 to M52)
                for ($minggu = 1; $minggu <= 52; $minggu++) {
                    $colIndex = $minggu + 1; // +1 because of No and Nama columns
                    if (isset($data[$colIndex])) {
                        $jumlahKasus = (int) $data[$colIndex];
                        
                        if ($jumlahKasus > 0) {
                            // Delete existing data for this week and disease
                            PenyakitData::where('tahun', $tahun)
                                ->where('nama_penyakit', $namaPenyakit)
                                ->where('minggu_ke', $minggu)
                                ->delete();
                            
                            // Insert new data
                            PenyakitData::create([
                                'nama_penyakit' => $namaPenyakit,
                                'minggu_ke' => $minggu,
                                'jumlah_kasus' => $jumlahKasus,
                                'tahun' => $tahun,
                            ]);
                            
                            $importedCount++;
                        }
                    }
                }
            }
            
            return redirect()->route('penyakit.index')->with('success', "Data 10 Besar Penyakit tahun {$tahun} berhasil diimport. Total {$importedCount} data ditambahkan.");
            
        } catch (\Exception $e) {
            return redirect()->route('penyakit.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
