<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;  // <- importa esta interfaz
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable implements JWTSubject  // <- implementa la interfaz
{
    use Notifiable, HasRoles;

    public const TYPE_ADMIN = 'admin';
    public const TYPE_AUCTIONEER = 'subastador';
    public const TYPE_BIDDER = 'ofertante';

    public const AUCTIONEER_NATURAL = 'natural';
    public const AUCTIONEER_COMPANY = 'empresa';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'contact',
        'signature_path',
        'password',
        'gender',
        'user_type',
        'plan_id',
        'plan_started_at',
        'plan_expires_at',
        'auctioneer_client_type',
        'document_type',
        'document_number',
        'address',
        'city',
        'company_name',
        'company_document_number',
        'company_legal_representative',
        'company_phone',
        'company_address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'plan_started_at' => 'date',
        'plan_expires_at' => 'date',
    ];

    // Métodos requeridos por JWTSubject:

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function auctionProducts(): HasMany
    {
        return $this->hasMany(AuctionProduct::class, 'auctioneer_id');
    }

    public function auctionBids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function requiresActivePlan(): bool
    {
        if ($this->user_type === self::TYPE_AUCTIONEER || $this->hasRole(self::TYPE_AUCTIONEER)) {
            return true;
        }

        return false;
    }

    public function hasActivePlan(): bool
    {
        return $this->plan_id !== null
            && $this->plan?->is_active
            && $this->plan_started_at !== null
            && $this->plan_expires_at !== null
            && $this->plan_expires_at->endOfDay()->isFuture();
    }

    public function planStatusMessage(): ?string
    {
        if (! $this->requiresActivePlan()) {
            return null;
        }

        if (! $this->plan_id || ! $this->plan?->is_active || ! $this->plan_started_at || ! $this->plan_expires_at) {
            return 'No tiene un plan activo asignado. Por favor contacte al administrador.';
        }

        if ($this->plan_expires_at->endOfDay()->isPast()) {
            return 'Su plan se venció el ' . $this->plan_expires_at->format('d/m/Y') . '. Por favor renueve el plan para ingresar.';
        }

        return null;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
