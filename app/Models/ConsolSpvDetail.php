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
        'name',
        'ee',
        'nego_value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ee' => 'decimal:2',
        'nego_value' => 'float',
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
