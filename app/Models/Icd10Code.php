<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icd10Code extends Model
{
    use HasFactory;

    protected $table = 'icd10s';

    protected $fillable = [
        'code',
        'display'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopeByCode($query, $code)
    {
        return $query->where('code', 'like', "%{$code}%");
    }

    public function scopeByDisplay($query, $display)
    {
        return $query->where('display', 'like', "%{$display}%");
    }

    // Accessors
    public function getFormattedCodeAttribute()
    {
        return strtoupper($this->code);
    }

    public function getShortDisplayAttribute()
    {
        return strlen($this->display) > 50 ? substr($this->display, 0, 50) . '...' : $this->display;
    }

    public function pasiens()
    {
        return $this->belongsToMany(Pasien::class, 'pasien_icd10');
    }
}
