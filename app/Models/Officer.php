<?php

namespace App\Models;

use App\Models\Facility;
use App\Models\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Officer extends Model
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

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function works()
    {
        return $this->belongsToMany(Work::class, 'officer_work');
    }
}
