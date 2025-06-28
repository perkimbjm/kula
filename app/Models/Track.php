<?php

namespace App\Models;

use App\Models\Work;
use App\Models\Officer;
use App\Enums\TrackStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Track extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_id',
        'survei',
        'pemilihan',
        'kontrak',
        'uang_muka',
        'kritis',
        'selesai',
        'pho',
        'aset',
        'ppk_dinas',
        'bendahara',
        'pengguna_anggaran',
        'keuangan',
        'bank',
        'laporan',
        'pemeriksa',
        'lat',
        'lng',
        'panjang',
        'lebar',
        'foto_survey',
        'foto_pho',
        'lampiran',
        'status',
        'catatan_tim_teknis',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'work_id' => 'integer',
        'survei' => 'boolean',
        'pemilihan' => 'boolean',
        'kontrak' => 'boolean',
        'uang_muka' => 'boolean',
        'kritis' => 'boolean',
        'selesai' => 'boolean',
        'pho' => 'boolean',
        'aset' => 'boolean',
        'ppk_dinas' => 'boolean',
        'bendahara' => 'boolean',
        'pengguna_anggaran' => 'boolean',
        'keuangan' => 'boolean',
        'bank' => 'boolean',
        'laporan' => 'boolean',
        'pemeriksa' => 'array',
        'lat' => 'string',
        'lng' => 'string',
        'panjang' => 'decimal:2',
        'lebar' => 'decimal:2',
        'foto_survey' => 'array',
        'foto_pho' => 'array',
        'lampiran' => 'array',
        'status' => TrackStatus::class,
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Mendapatkan progress terakhir berdasarkan urutan checkbox yang dicentang
     */
    public function getLatestProgressAttribute(): string
    {
        $progressSteps = [
            'survei' => 'Survei',
            'pemilihan' => 'Pemilihan',
            'kontrak' => 'Kontrak',
            'uang_muka' => 'Uang Muka',
            'kritis' => 'Kritis',
            'selesai' => 'Selesai',
            'pho' => 'PHO',
            'aset' => 'Aset',
            'ppk_dinas' => 'PPK Dinas',
            'bendahara' => 'Bendahara',
            'pengguna_anggaran' => 'Pengguna Anggaran',
            'keuangan' => 'Keuangan',
            'bank' => 'Bank',
            'laporan' => 'Laporan',
        ];

        $latestProgress = 'Belum Dimulai';

        foreach ($progressSteps as $field => $label) {
            if ($this->$field) {
                $latestProgress = $label;
            }
        }

        return $latestProgress;
    }



    /**
     * Accessor untuk mendapatkan contract number dari work
     */
    public function getContractNumberAttribute()
    {
        return $this->work?->contract_number ?? '-';
    }

    /**
     * Accessor untuk mendapatkan consultant name dari work
     */
    public function getConsultantNameAttribute()
    {
        return $this->work?->consultant?->name ?? '-';
    }

    /**
     * Accessor untuk mendapatkan supervisor name dari work
     */
    public function getSupervisorNameAttribute()
    {
        return $this->work?->supervisor?->name ?? '-';
    }

    /**
     * Accessor untuk mendapatkan contractor name dari work
     */
    public function getContractorNameAttribute()
    {
        return $this->work?->contractor?->name ?? '-';
    }

    /**
     * Accessor untuk mendapatkan work year dari work
     */
    public function getWorkYearAttribute()
    {
        return $this->work?->year ?? null;
    }

    /**
     * Helper method untuk mendapatkan nama pemeriksa
     */
    public function getPemeriksaNamesAttribute()
    {
        if (!$this->pemeriksa || !is_array($this->pemeriksa)) {
            return [];
        }

        return Officer::whereIn('id', $this->pemeriksa)->pluck('name')->toArray();
    }

    /**
     * Helper method untuk mendapatkan string pemeriksa yang di-join dengan koma
     */
    public function getPemeriksaStringAttribute()
    {
        $names = $this->pemeriksa_names;
        return !empty($names) ? implode(', ', $names) : '-';
    }
}
