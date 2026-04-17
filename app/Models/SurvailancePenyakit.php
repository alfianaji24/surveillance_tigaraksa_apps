<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvailancePenyakit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_penyakit',
        'icd10_codes',
        'kategori',
        'aktif',
    ];

    protected $casts = [
        'icd10_codes' => 'array',
        'aktif' => 'boolean',
    ];

    /**
     * Get all ICD-10 codes for this disease
     */
    public function getIcd10CodesAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Check if an ICD-10 code belongs to this disease
     */
    public function hasIcd10Code($code)
    {
        return in_array(strtoupper($code), $this->icd10_codes);
    }

    /**
     * Get active diseases
     */
    public static function getActive()
    {
        return self::where('aktif', true)->orderBy('nama_penyakit')->get();
    }

    /**
     * Find disease by ICD-10 code
     */
    public static function findByIcd10Code($code)
    {
        $diseases = self::where('aktif', true)->get();
        
        foreach ($diseases as $disease) {
            if ($disease->hasIcd10Code($code)) {
                return $disease;
            }
        }
        
        return null;
    }

    /**
     * Get disease statistics for given period
     */
    public function getStatistics($year = null, $week = null)
    {
        $query = \App\Models\Pasien::whereHas('icd10Codes', function($q) {
            $q->whereIn('code', $this->icd10_codes);
        });

        if ($year) {
            $query->whereRaw("strftime('%Y', tanggal_kunjungan) = ?", [$year]);
        }

        if ($week) {
            // SQLite compatible week calculation
            $query->whereRaw("strftime('%W', tanggal_kunjungan) = ?", [str_pad($week, 2, '0', STR_PAD_LEFT)]);
        }

        return $query->count();
    }

    /**
     * Get weekly statistics for a year
     */
    public function getWeeklyStatistics($year)
    {
        // Cache key for performance
        $cacheKey = "weekly_stats_{$this->kode}_{$year}";
        
        // Try to get from cache first
        $cachedData = cache()->get($cacheKey);
        if ($cachedData) {
            return $cachedData;
        }
        
        $statistics = [];
        
        for ($week = 1; $week <= 52; $week++) {
            $count = $this->getStatistics($year, $week);
            $statistics[$week] = $count;
        }
        
        // Cache for 30 minutes
        cache()->put($cacheKey, $statistics, 1800);
        
        return $statistics;
    }

    /**
     * Get monthly statistics for a year
     */
    public function getMonthlyStatistics($year)
    {
        // Cache key for performance
        $cacheKey = "monthly_stats_{$this->kode}_{$year}";
        
        // Try to get from cache first
        $cachedData = cache()->get($cacheKey);
        if ($cachedData) {
            return $cachedData;
        }
        
        $statistics = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $count = \App\Models\Pasien::whereHas('icd10Codes', function($q) {
                $q->whereIn('code', $this->icd10_codes);
            })->whereRaw("strftime('%Y', tanggal_kunjungan) = ?", [$year])
              ->whereRaw("strftime('%m', tanggal_kunjungan) = ?", [str_pad($month, 2, '0', STR_PAD_LEFT)])
              ->count();
            
            $statistics[$month] = $count;
        }
        
        // Cache for 30 minutes
        cache()->put($cacheKey, $statistics, 1800);
        
        return $statistics;
    }
}
