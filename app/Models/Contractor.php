<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contractor extends Model
{
    use HasFactory;

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
        'slug',
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

    public function constructions(): HasMany
    {
        return $this->hasMany(Construction::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
}
