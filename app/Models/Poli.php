<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poli extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the pasien records for the poli.
     */
    public function pasien()
    {
        return $this->hasMany(Pasien::class);
    }
    
    /**
     * Get the pasiens records for the poli (alias).
     */
    public function pasiens()
    {
        return $this->hasMany(Pasien::class);
    }

    /**
     * Scope to get only active polis
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search polis by name or code
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode', 'like', "%{$search}%");
        });
    }

    /**
     * Get formatted status
     */
    public function getStatusAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }
}
