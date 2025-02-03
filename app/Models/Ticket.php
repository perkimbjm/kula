<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\CommonMark\CommonMarkConverter;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'issue',
        'district_id',
        'village_id',
        'photo_url',
        'lat',
        'lng',
        'status',
        'ticket_number'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'district_id' => 'integer',
        'village_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function ticketResponses(): HasMany
    {
        return $this->hasMany(TicketResponse::class);
    }

    public function feedback()
    {
        return $this->hasMany(TicketFeedback::class);
    }

   
    public function latestResponse()
    {
        return $this->ticketResponses()->latest()->first();
    }

    // Atribut untuk mendapatkan response
    public function getLatestResponseAttribute()
    {
        return $this->latestResponse() ? $this->latestResponse()->response : 'Belum ada tanggapan';
    }

    public function getRenderedIssueAttribute()
    {
        $converter = new CommonMarkConverter();
        return $converter->convertToHtml($this->issue);
    }
        
    protected static function booted()
    {
        static::creating(function ($ticket) {
            // Buat short_id saat tiket dibuat
            $ticket->ticket_number = substr($ticket->id, 0, 8); // Ambil 8 karakter pertama dari UUID
        });
    }
}
