<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    public const TYPE_AUCTIONEER = User::TYPE_AUCTIONEER;
    public const TYPE_BIDDER = User::TYPE_BIDDER;

    public const DURATION_ONE_MONTH = 1;
    public const DURATION_SIX_MONTHS = 6;
    public const DURATION_ONE_YEAR = 12;

    public const DURATIONS = [
        self::DURATION_ONE_MONTH,
        self::DURATION_SIX_MONTHS,
        self::DURATION_ONE_YEAR,
    ];

    protected $fillable = [
        'user_type',
        'duration_months',
        'price',
        'is_active',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'plan_id');
    }

    public function getNameAttribute(): string
    {
        return ($this->user_type === self::TYPE_AUCTIONEER ? 'Plan subastador' : 'Plan ofertante')
            . ' - ' . $this->durationLabel();
    }

    public function durationLabel(): string
    {
        return match ($this->duration_months) {
            self::DURATION_ONE_MONTH => '1 mes',
            self::DURATION_SIX_MONTHS => '6 meses',
            self::DURATION_ONE_YEAR => '1 año',
            default => $this->duration_months . ' meses',
        };
    }

    public static function durationOptions(): array
    {
        return [
            self::DURATION_ONE_MONTH => '1 mes',
            self::DURATION_SIX_MONTHS => '6 meses',
            self::DURATION_ONE_YEAR => '1 año',
        ];
    }

    public static function userTypeLabels(): array
    {
        return [
            self::TYPE_AUCTIONEER => 'Subastadores',
            self::TYPE_BIDDER => 'Ofertantes',
        ];
    }
}
