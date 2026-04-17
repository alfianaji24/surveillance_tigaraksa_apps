<?php

namespace App\Http\Controllers;

use App\Models\SurvailancePenyakit;
use App\Models\Pasien;
use Illuminate\Http\Request;

class SurvailanceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $week = $request->get('week', null);
        
        // Get all active diseases
        $diseases = SurvailancePenyakit::getActive();
        
        // Get statistics for each disease
        $statistics = [];
        $totalCases = 0;
        
        foreach ($diseases as $disease) {
            $count = $disease->getStatistics($year, $week);
            $statistics[] = [
                'kode' => $disease->kode,
                'nama_penyakit' => $disease->nama_penyakit,
                'kategori' => $disease->kategori,
                'jumlah_kasus' => $count,
                'icd10_codes' => $disease->icd10_codes
            ];
            $totalCases += $count;
        }
        
        // Get weekly data for charts
        $weeklyData = [];
        foreach ($diseases as $disease) {
            $weeklyData[$disease->kode] = $disease->getWeeklyStatistics($year);
        }
        
        // Get monthly data for charts
        $monthlyData = [];
        foreach ($diseases as $disease) {
            $monthlyData[$disease->kode] = $disease->getMonthlyStatistics($year);
        }
        
        // Get available years
        $availableYears = Pasien::selectRaw('strftime("%Y", tanggal_kunjungan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Get current week info
        $currentWeek = date('W');
        $currentYear = date('Y');
        
        return view('survailance.dashboard', compact(
            'statistics',
            'weeklyData',
            'monthlyData',
            'diseases',
            'year',
            'week',
            'currentWeek',
            'currentYear',
            'availableYears',
            'totalCases'
        ));
    }
    
    public function getChartData(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $type = $request->get('type', 'weekly'); // weekly or monthly
        $diseaseKode = $request->get('disease');
        
        // Cache key for performance
        $cacheKey = "chart_data_{$diseaseKode}_{$year}_{$type}";
        
        // Try to get from cache first
        $cachedData = cache()->get($cacheKey);
        if ($cachedData) {
            return response()->json($cachedData);
        }
        
        $disease = SurvailancePenyakit::where('kode', $diseaseKode)->first();
        
        if (!$disease) {
            return response()->json(['error' => 'Disease not found'], 404);
        }
        
        if ($type === 'weekly') {
            $data = $disease->getWeeklyStatistics($year);
            $labels = array_map(function($week) {
                return "Minggu $week";
            }, range(1, 52));
        } else {
            $data = $disease->getMonthlyStatistics($year);
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        
        $result = [
            'labels' => $labels,
            'data' => array_values($data),
            'disease' => $disease->nama_penyakit
        ];
        
        // Cache for 1 hour
        cache()->put($cacheKey, $result, 3600);
        
        return response()->json($result);
    }
    
    public function getTopDiseases(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $limit = $request->get('limit', 10);
        
        $diseases = SurvailancePenyakit::getActive();
        $statistics = [];
        
        foreach ($diseases as $disease) {
            $count = $disease->getStatistics($year);
            $statistics[] = [
                'kode' => $disease->kode,
                'nama_penyakit' => $disease->nama_penyakit,
                'jumlah_kasus' => $count
            ];
        }
        
        // Sort by case count
        usort($statistics, function($a, $b) {
            return $b['jumlah_kasus'] - $a['jumlah_kasus'];
        });
        
        return response()->json(array_slice($statistics, 0, $limit));
    }
    
    public function getDiseaseDetails(Request $request)
    {
        $diseaseKode = $request->get('disease');
        $year = $request->get('year', date('Y'));
        $week = $request->get('week', null);
        
        $disease = SurvailancePenyakit::where('kode', $diseaseKode)->first();
        
        if (!$disease) {
            return response()->json(['error' => 'Disease not found'], 404);
        }
        
        $patients = Pasien::whereHas('icd10Codes', function($q) use ($disease) {
            $q->whereIn('code', $disease->icd10_codes);
        })->whereRaw("strftime('%Y', tanggal_kunjungan) = ?", [$year]);
        
        if ($week) {
            $patients->whereRaw("strftime('%W', tanggal_kunjungan) = ?", [str_pad($week, 2, '0', STR_PAD_LEFT)]);
        }
        
        $patients = $patients->with('icd10Codes')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(20);
        
        return response()->json([
            'disease' => $disease,
            'patients' => $patients
        ]);
    }
    
    public function updateDisease(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10',
            'nama_penyakit' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'aktif' => 'required|boolean',
            'icd10_codes' => 'required|array|min:1',
            'icd10_codes.*' => 'required|string|max:10'
        ]);
        
        try {
            $disease = SurvailancePenyakit::where('kode', $request->kode)->first();
            
            if (!$disease) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penyakit tidak ditemukan'
                ], 404);
            }
            
            // Update disease data
            $disease->update([
                'nama_penyakit' => $request->nama_penyakit,
                'kategori' => $request->kategori,
                'aktif' => $request->aktif,
                'icd10_codes' => $request->icd10_codes
            ]);
            
            // Clear cache for this disease
            $currentYear = date('Y');
            cache()->forget("weekly_stats_{$request->kode}_{$currentYear}");
            cache()->forget("monthly_stats_{$request->kode}_{$currentYear}");
            cache()->forget("chart_data_{$request->kode}_{$currentYear}_weekly");
            cache()->forget("chart_data_{$request->kode}_{$currentYear}_monthly");
            
            return response()->json([
                'success' => true,
                'message' => 'Data penyakit berhasil diperbarui',
                'disease' => $disease
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }
}
