<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Construction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'procurement_officer_id',
        'oe',
        'bid_value',
        'correction_value',
        'nego_value',
        'contractor_id',
        'invite_date',
        'evaluation_date',
        'nego_date',
        'BAHPL_date',
        'sppbj_date',
        'spk_date',
        'account_type',
        'program',
        'duration',
        'district_id',
        'location',
        'consultant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'procurement_officer_id' => 'integer',
        'oe' => 'float',
        'bid_value' => 'float',
        'correction_value' => 'float',
        'nego_value' => 'float',
        'contractor_id' => 'integer',
        'invite_date' => 'date',
        'evaluation_date' => 'date',
        'nego_date' => 'date',
        'BAHPL_date' => 'date',
        'sppbj_date' => 'date',
        'spk_date' => 'date',
        'district_id' => 'integer',
        'consultant_id' => 'integer',
    ];

    public function procurementOfficer(): BelongsTo
    {
        return $this->belongsTo(ProcurementOfficer::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}
