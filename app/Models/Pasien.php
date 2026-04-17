<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_kunjungan',
        'poli',
        'poli_id',
        'no_rekam_medik',
        'nik',
        'no_bpjs',
        'nama_pasien',
        'alamat',
        'no_hp',
        'tanggal_lahir',
        'umur',
        'jenis_kelamin',
        'jenis_pasien',
        'jenis_bayar',
        'anamnesa',
        'diagnosa',
        'pemeriksa',
        'status',
        'status_active',
        'rs_rujukan'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'tanggal_lahir' => 'date',
        'status_active' => 'boolean',
    ];

    public function icd10Codes()
    {
        return $this->belongsToMany(Icd10Code::class, 'pasien_icd10', 'pasien_id', 'icd10_code_id', 'id', 'id');
    }

    /**
     * Get the poli that owns the pasien.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    /**
     * Get the diagnosa PKM records for this patient.
     */
    public function diagnosaPkm()
    {
        return $this->hasMany(DiagnosaPKM::class, 'no_rekam_medik', 'no_rekam_medik');
    }

    /**
     * Get the latest visit from DiagnosaPKM.
     */
    public function latestVisit()
    {
        return $this->hasOne(DiagnosaPKM::class, 'no_rekam_medik', 'no_rekam_medik')
            ->latest('tanggal_kunjungan');
    }

    /**
     * Get total visit count.
     */
    public function getTotalVisitsAttribute()
    {
        return $this->diagnosaPkm()->count();
    }

    /**
     * Get hash ID for the pasien
     */
    public function getHashIdAttribute()
    {
        $hashids = new Hashids('pasien-salt', 10);
        return $hashids->encode($this->id);
    }

    /**
     * Find pasien by hash ID
     */
    public static function findByHashId($hashId)
    {
        $hashids = new Hashids('pasien-salt', 10);
        $decoded = $hashids->decode($hashId);
        
        if (empty($decoded)) {
            return null;
        }
        
        return self::find($decoded[0]);
    }

    /**
     * Find pasien by hash ID or fail
     */
    public static function findByHashIdOrFail($hashId)
    {
        $pasien = self::findByHashId($hashId);
        if (!$pasien) {
            abort(404, 'Pasien not found');
        }
        return $pasien;
    }

    /**
     * Get route key for implicit model binding
     */
    public function getRouteKeyName()
    {
        return 'hash_id';
    }
}
