<?php

namespace App\Models;

use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{

    use HasRoles, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        // Cek apakah string password panjangnya 60 karakter (standar bcrypt hash)
        // dan mulai dengan $2y$ (format bcrypt)
        if (strlen($value) === 60 && str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = $value;
        } else {
            // Jika bukan hash bcrypt, maka hash dengan bcrypt
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'email_verified_at' => 'timestamp',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketFeedbacks(): HasMany
    {
        return $this->hasMany(TicketFeedback::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function unsetPassword()  
    {  
        $this->unsetRelation('password');  
        $this->attributes['password'] = null;  
    }  
}
