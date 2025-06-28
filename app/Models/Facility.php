<?php

namespace App\Models;

use App\Models\Work;
use App\Models\Officer;
use App\Enums\ProgressStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Facility extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_id',
        'rt',
        'length',
        'width',
        'phone',
        'construct_type',
        'lat',
        'lng',
        'progress_status',
        'real_1',
        'real_2',
        'real_3',
        'real_4',
        'real_5',
        'real_6',
        'note',
        'photo_0',
        'photo_0_url',
        'photo_50',
        'photo_50_url',
        'photo_100',
        'photo_100_url',
        'photo_pho',
        'photo_pho_url',
        'shop_drawing',
        'shop_drawing_url',
        'asbuilt_drawing',
        'asbuilt_drawing_url',
        'rab',
        'rab_url',
        'laporan',
        'laporan_url',
        'file_shp',
        'file_shp_url',
        'file_konsultan_perencana',
        'file_konsultan_perencana_url',
        'file_konsultan_pengawas',
        'file_konsultan_pengawas_url',
        'file_kontraktor_pelaksana',
        'file_kontraktor_pelaksana_url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'work_id' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
        'length' => 'float',
        'width' => 'float',
        'real_1' => 'float',
        'real_2' => 'float',
        'real_3' => 'float',
        'real_4' => 'float',
        'real_5' => 'float',
        'real_6' => 'float',
        'progress_status' => ProgressStatus::class,
        'laporan' => 'array',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Accessor untuk mendapatkan contract number dari work
     */
    public function getContractNumberDisplayAttribute()
    {
        return $this->work?->contract_number ?? '-';
    }

    /**
     * Accessor untuk mendapatkan technical team string dari work
     */
    public function getTechnicalTeamDisplayAttribute()
    {
        return $this->work?->technical_team_string ?? '-';
    }

    /**
     * Accessor untuk mendapatkan procurement officer name dari work
     */
    public function getProcurementOfficerDisplayAttribute()
    {
        return $this->work?->procurementOfficer?->name ?? '-';
    }

    /**
     * Accessor untuk mendapatkan district name dari work
     */
    public function getDistrictDisplayAttribute()
    {
        return $this->work?->district?->name ?? '-';
    }

    /**
     * Accessor untuk mendapatkan village name dari work
     */
    public function getVillageDisplayAttribute()
    {
        return $this->work?->village?->name ?? '-';
    }

    /**
     * Helper method untuk mendapatkan file atau URL
     */
    public function getFileOrUrl($fileField, $urlField)
    {
        if ($this->$fileField) {
            return asset('storage/' . $this->$fileField);
        }

        if ($this->$urlField) {
            return $this->$urlField;
        }

        return null;
    }

    /**
     * Helper method untuk mendapatkan semua file dan URL laporan
     */
    public function getAllLaporanFiles()
    {
        $files = [];

        // File upload
        if ($this->laporan && is_array($this->laporan)) {
            foreach ($this->laporan as $file) {
                $files[] = [
                    'type' => 'file',
                    'url' => asset('storage/' . $file),
                    'name' => basename($file)
                ];
            }
        }

        // URL
        if ($this->laporan_url) {
            $urls = explode("\n", trim($this->laporan_url));
            foreach ($urls as $url) {
                $url = trim($url);
                if ($url) {
                    $files[] = [
                        'type' => 'url',
                        'url' => $url,
                        'name' => 'URL: ' . parse_url($url, PHP_URL_HOST)
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Helper method untuk menghitung total file dan URL yang tersedia
     */
    public function getTotalFilesAttribute()
    {
        $count = 0;

        $fileFields = [
            'photo_0', 'photo_50', 'photo_100', 'photo_pho',
            'shop_drawing', 'asbuilt_drawing', 'rab', 'file_shp',
            'file_konsultan_perencana', 'file_konsultan_pengawas', 'file_kontraktor_pelaksana'
        ];

        $urlFields = [
            'photo_0_url', 'photo_50_url', 'photo_100_url', 'photo_pho_url',
            'shop_drawing_url', 'asbuilt_drawing_url', 'rab_url', 'file_shp_url',
            'file_konsultan_perencana_url', 'file_konsultan_pengawas_url', 'file_kontraktor_pelaksana_url'
        ];

        // Hitung file upload
        foreach ($fileFields as $field) {
            if ($this->$field) {
                if ($field === 'laporan' && is_array($this->laporan)) {
                    $count += count($this->laporan);
                } else {
                    $count++;
                }
            }
        }

        // Hitung URL
        foreach ($urlFields as $field) {
            if ($this->$field) {
                if ($field === 'laporan_url') {
                    $urls = array_filter(explode("\n", trim($this->$field)));
                    $count += count($urls);
                } else {
                    $count++;
                }
            }
        }

        return $count;
    }

}
