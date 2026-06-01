<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;  // <- importa esta interfaz
use Illuminate\Notifications\Notifiable;
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
