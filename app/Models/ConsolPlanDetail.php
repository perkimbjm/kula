<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsolPlanDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nego_value',
        'ee',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nego_value' => 'float',
        'ee' => 'decimal:2',
    ];

    public function consolPlan(): BelongsTo
    {
        return $this->belongsTo(ConsolPlan::class);
    }

    public function consolidation(): BelongsTo
    {
        return $this->belongsTo(ConsolPlan::class);
    }
}
