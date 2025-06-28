<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Consultant extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'akta',
        'founding_date',
        'notary',
        'address',
        'npwp',
        'leader',
        'position',
        'bank',
        'account_number',
        'account_holder',
        'header_scan',
        'account_scan',
        'npwp_scan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'founding_date' => 'date',
    ];

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function constructions(): HasMany
    {
        return $this->hasMany(Construction::class);
    }

    public function spvs(): HasMany
    {
        return $this->hasMany(Spv::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function consolSpvs(): HasMany
    {
        return $this->hasMany(ConsolSpv::class);
    }

    public function consolPlans(): HasMany
    {
        return $this->hasMany(ConsolPlan::class);
    }
}
