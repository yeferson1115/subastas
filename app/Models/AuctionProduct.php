<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
