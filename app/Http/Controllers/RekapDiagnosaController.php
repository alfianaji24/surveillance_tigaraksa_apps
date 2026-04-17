<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class RekapDiagnosaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-rekap-diagnosa')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun = request('tahun', date('Y'));
        
        // Ambil data pasien berdasarkan tahun dan group by diagnosa
        $rekapData = Pasien::whereYear('tanggal_kunjungan', $tahun)
            ->selectRaw('diagnosa, COUNT(*) as total, COUNT(DISTINCT nama_pasien) as jumlah_pasien')
            ->groupBy('diagnosa')
            ->orderBy('total', 'desc')
            ->get();

        // Statistik tahunan
        $totalPasien = Pasien::whereYear('tanggal_kunjungan', $tahun)->count();
        $totalDiagnosa = $rekapData->count();
        $topDiagnosa = $rekapData->first();

        // Data untuk chart
        $chartData = $rekapData->take(10)->map(function($item) {
            return [
                'diagnosa' => $item->diagnosa,
                'total' => $item->total,
                'jumlah_pasien' => $item->jumlah_pasien
            ];
        });

        return view('rekap-diagnosa.index', compact('rekapData', 'tahun', 'totalPasien', 'totalDiagnosa', 'topDiagnosa', 'chartData'));
    }
}
