<?php

namespace App\Models;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Work extends Model
{
    use HasFactory;

    /**
     * Default relationships to eager load
     *
     * @var array
     */
    protected $with = [
        'district:id,name',
        'village:id,name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'name',
        'contract_number',
        'contractor_id',
        'consultant_id',
        'supervisor_id',
        'district_id',
        'village_id',
        'rt',
        'length',
        'width',
        'phone',
        'construction_type',
        'coordinate_lat',
        'coordinate_lng',
        'account_code',
        'program',
        'source',
        'duration',
        'technical_team',
        'procurement_officer_id',
        'hps',
        'bid_value',
        'correction_value',
        'nego_value',
        'invite_date',
        'evaluation_date',
        'nego_date',
        'bahpl_date',
        'sppbj_date',
        'spk_date',
        'add_number',
        'addendum_date',
        'addendum_value',
        'completion_letter',
        'completion_date',
        'pho_date',
        'advance_bap_number',
        'advance_guarantee_number',
        'advance_guarantor',
        'advance_guarantee_date',
        'advance_value',
        'advance_payment_date',
        'final_bap_number',
        'maintenance_guarantee_number',
        'final_guarantor',
        'final_guarantee_date',
        'final_guarantee_value',
        'final_payment_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contract_date' => 'date',
        'contractor_id' => 'integer',
        'consultant_id' => 'integer',
        'supervisor_id' => 'integer',
        'contract_value' => 'float',
        'district_id' => 'integer',
        'village_id' => 'integer',
        'length' => 'float',
        'width' => 'float',
        'duration' => 'integer',
        'technical_team' => 'array',
        'procurement_officer_id' => 'integer',
        'hps' => 'float',
        'bid_value' => 'float',
        'correction_value' => 'float',
        'nego_value' => 'float',
        'invite_date' => 'date',
        'evaluation_date' => 'date',
        'nego_date' => 'date',
        'bahpl_date' => 'date',
        'sppbj_date' => 'date',
        'spk_date' => 'date',
        'add_number' => 'string',
        'addendum_date' => 'date',
        'addendum_value' => 'float',
        'completion_date' => 'date',
        'pho_date' => 'date',
        'advance_guarantee_date' => 'date',
        'advance_value' => 'float',
        'advance_payment_date' => 'date',
        'final_guarantee_date' => 'date',
        'final_guarantee_value' => 'float',
        'final_payment_date' => 'date',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function procurementOfficer(): BelongsTo
    {
        return $this->belongsTo(ProcurementOfficer::class);
    }

    public function officers()
    {
        return $this->belongsToMany(Officer::class, 'officer_work');
    }

    /**
     * Scope untuk loading relasi lengkap saat diperlukan
     */
    public function scopeWithFullRelations(Builder $query): Builder
    {
        return $query->with([
            'district:id,name',
            'village:id,name',
            'contractor:id,name',
            'consultant:id,name',
            'supervisor:id,name',
            'officers:id,name',
            'procurementOfficer:id,name'
        ]);
    }

    /**
     * Scope untuk export data
     */
    public function scopeForExport(Builder $query): Builder
    {
        return $query->withFullRelations();
    }

    /**
     * Accessor untuk mendapatkan nama tim teknis dari relasi officers
     */
    public function getTechnicalTeamNamesAttribute()
    {
        return $this->officers ? $this->officers->pluck('name')->toArray() : [];
    }

    /**
     * Accessor untuk mendapatkan string tim teknis yang di-join dengan koma
     */
    public function getTechnicalTeamStringAttribute()
    {
        return $this->officers ? $this->officers->pluck('name')->implode(', ') : '-';
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }
}
