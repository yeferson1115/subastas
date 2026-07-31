<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classified extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'title',
        'slug',
        'images',
        'description',
        'contact',
        'price',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $digits = preg_replace('/\D+/', '', $this->contact ?? '');

        if (! $digits || strlen($digits) < 7) {
            return null;
        }

        return 'https://wa.me/' . $digits . '?text=' . urlencode('Hola, estoy interesado en el clasificado: ' . $this->title);
    }
}
