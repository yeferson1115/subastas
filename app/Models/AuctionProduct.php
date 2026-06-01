<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuctionProduct extends Model
{
    use HasFactory;

    public const TYPE_VEHICLE = 'Vehiculo';
    public const TYPE_MOTORCYCLE = 'Moto';
    public const TYPE_MACHINERY = 'Maquinaria';
    public const TYPE_SCRAP = 'Chatarra';
    public const TYPE_REAL_ESTATE = 'Propiedad raiz';
    public const TYPE_FURNITURE = 'mobiliario';

    protected $fillable = [
        'auctioneer_id',
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'auction_start_date',
        'auction_end_date',
        'auction_start_time',
        'auction_end_time',
        'base_price',
        'technical_sheet_path',
        'terms_path',
        'product_type',
        'product_details',
        'location',
        'contact_phone',
        'mandatory_visit',
        'quantity',
        'detail',
        'images',
    ];

    protected $casts = [
        'auction_start_date' => 'date',
        'auction_end_date' => 'date',
        'base_price' => 'decimal:2',
        'mandatory_visit' => 'boolean',
        'product_details' => 'array',
        'images' => 'array',
    ];

    public static function productTypes(): array
    {
        return [
            self::TYPE_VEHICLE,
            self::TYPE_MOTORCYCLE,
            self::TYPE_MACHINERY,
            self::TYPE_SCRAP,
            self::TYPE_REAL_ESTATE,
            self::TYPE_FURNITURE,
        ];
    }

    public function auctioneer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auctioneer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class)->orderByDesc('amount')->orderBy('created_at');
    }

    public function highestBid(): HasMany
    {
        return $this->hasMany(AuctionBid::class)->orderByDesc('amount')->orderBy('created_at');
    }

    public function getStartsAtAttribute(): ?Carbon
    {
        return $this->combineAuctionDateTime($this->auction_start_date, $this->auction_start_time);
    }

    public function getEndsAtAttribute(): ?Carbon
    {
        return $this->combineAuctionDateTime($this->auction_end_date, $this->auction_end_time);
    }

    public function getIsActiveAttribute(): bool
    {
        $now = now();

        return $this->starts_at !== null
            && $this->ends_at !== null
            && $this->starts_at->lessThanOrEqualTo($now)
            && $this->ends_at->greaterThanOrEqualTo($now);
    }

    public function getIsFinishedAttribute(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    public function getAuctionStatusAttribute(): string
    {
        if ($this->is_finished) {
            return 'Terminada';
        }

        if ($this->is_active) {
            return 'Activa';
        }

        return 'Programada';
    }

    private function combineAuctionDateTime($date, ?string $time): ?Carbon
    {
        if (! $date || ! $time) {
            return null;
        }

        return Carbon::parse($date->format('Y-m-d') . ' ' . $time);
    }
}
