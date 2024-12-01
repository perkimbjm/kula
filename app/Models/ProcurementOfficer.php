<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementOfficer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nip',
        'grade',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

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
