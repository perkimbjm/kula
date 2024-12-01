<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'name',
        'contract_date',
        'contract_number',
        'contractor_id',
        'consultant_id',
        'supervisor_id',
        'contract_value',
        'progress',
        'cutoff',
        'status',
        'paid',
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
        'progress' => 'float',
        'cutoff' => 'date',
        'paid' => 'float',
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
}
