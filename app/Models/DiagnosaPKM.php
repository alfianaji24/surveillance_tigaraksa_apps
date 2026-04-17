<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiagnosaPKM extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_kunjungan',
        'poli',
        'no_rekam_medik',
        'nik',
        'nama_pasien',
        'alamat',
        'tanggal_lahir',
        'umur',
        'jenis_kelamin',
        'jenis_pasien',
        'no_bpjs',
        'jenis_bayar',
        'anamnesa',
        'diagnosa',
        'pemeriksa',
        'status',
        'rs_rujukan',
        'kode_icd_10'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_kunjungan' => 'date',
        'umur' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto generate umur from tanggal_lahir
        static::saving(function ($model) {
            if ($model->tanggal_lahir) {
                $model->umur = Carbon::parse($model->tanggal_lahir)->age;
            }
        });

        // Auto set no_bpjs to null if jenis_pasien is not BPJS
        static::saving(function ($model) {
            if ($model->jenis_pasien !== 'BPJS') {
                $model->no_bpjs = null;
            }
        });

        // Auto set rs_rujukan to null if status is not Rujuk
        static::saving(function ($model) {
            if ($model->status !== 'Rujuk') {
                $model->rs_rujukan = null;
            }
        });
    }

    /**
     * Get extracted ICD-10 code from diagnosa
     */
    public function getKodeIcd10Attribute()
    {
        if (!$this->diagnosa) {
            return null;
        }

        // Extract first word/code from diagnosa text
        $words = explode(' ', trim($this->diagnosa));
        return $words[0] ?? null;
    }

    /**
     * Get formatted umur
     */
    public function getUmurFormattedAttribute()
    {
        return $this->umur ? $this->umur . ' tahun' : '-';
    }

    /**
     * Get the pasien bank data for this visit.
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rekam_medik', 'no_rekam_medik');
    }

    /**
     * Get the ICD10 codes for this diagnosis.
     */
    public function icd10Codes()
    {
        return $this->belongsToMany(Icd10Code::class, 'pasien_icd10', 'pasien_id', 'icd10_code_id', 'id', 'id');
    }
}
