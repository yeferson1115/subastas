<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function auctionProducts(): HasMany
    {
        return $this->hasMany(AuctionProduct::class);
    }

    public function classifieds(): HasMany
    {
        return $this->hasMany(Classified::class);
    }
}
