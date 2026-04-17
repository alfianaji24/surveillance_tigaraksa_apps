<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyakitData extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penyakit',
        'minggu_ke',
        'jumlah_kasus',
        'tahun',
    ];

    protected $casts = [
        'minggu_ke' => 'integer',
        'jumlah_kasus' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Get data for specific year and week
     */
    public static function getByYearAndWeek($tahun, $minggu_ke = null)
    {
        $query = self::where('tahun', $tahun);
        
        if ($minggu_ke) {
            $query->where('minggu_ke', $minggu_ke);
        }
        
        return $query->orderBy('nama_penyakit')->orderBy('minggu_ke')->get();
    }

    /**
     * Get top 10 diseases by total cases in a year
     */
    public static function getTop10ByYear($tahun)
    {
        return self::select('nama_penyakit', \DB::raw('SUM(jumlah_kasus) as total_kasus'))
            ->where('tahun', $tahun)
            ->groupBy('nama_penyakit')
            ->orderByDesc('total_kasus')
            ->limit(10)
            ->get();
    }

    /**
     * Get weekly data for all diseases in a year
     */
    public static function getWeeklyDataByYear($tahun)
    {
        $data = [];
        $penyakitList = [
            'Demam Berdarah', 'Tifus', 'Diare', 'ISPA', 'Hepatitis',
            'TBC', 'Malaria', 'Cacar Air', 'Campak', 'Leptospirosis'
        ];

        foreach ($penyakitList as $penyakit) {
            $weeklyData = self::where('tahun', $tahun)
                ->where('nama_penyakit', $penyakit)
                ->orderBy('minggu_ke')
                ->pluck('jumlah_kasus', 'minggu_ke')
                ->toArray();
            
            $data[$penyakit] = $weeklyData;
        }

        return $data;
    }
}
