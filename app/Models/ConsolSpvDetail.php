<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsolSpvDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consolidation_id',
        'budget',
        'name',
        'nego_value',
        'consol_spv_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consolidation_id' => 'integer',
        'budget' => 'float',
        'nego_value' => 'float',
        'consol_spv_id' => 'integer',
    ];

    public function consolSpv(): BelongsTo
    {
        return $this->belongsTo(ConsolSpv::class);
    }

    public function consolidation(): BelongsTo
    {
        return $this->belongsTo(ConsolSpv::class);
    }
}
