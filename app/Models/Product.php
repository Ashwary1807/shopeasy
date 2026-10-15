<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'category',
        'image',
        'stock',
        'status',
        'rating',
        'reviews_count',
        'badge',
        'badge_color',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'stock' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
    ];

    /**
     * Calculate discount percentage if original_price is greater than current price.
     */
    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
        }

        return null;
    }

    /**
     * Provide a fallback badge color class based on the badge label.
     */
    public function getBadgeColorAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return match ($this->badge) {
            'Best Seller' => 'bg-amber-500',
            'Featured', 'Top Rated' => 'bg-emerald-600',
            'Hot Deal', 'Sale' => 'bg-rose-500',
            'New Arrival' => 'bg-indigo-600',
            'Popular' => 'bg-blue-600',
            default => 'bg-brand-600',
        };
    }

    /**
     * Determine if product is in stock and available for purchase.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0 && $this->status === 'active';
    }

    /**
     * Get the order line items referencing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all customer reviews for this product.
     *
     * @return HasMany<ProductReview>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get approved customer reviews for this product.
     *
     * @return HasMany<ProductReview>
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

    /**
     * Recalculate and persist the cached rating and reviews count from approved reviews.
     */
    public function recalculateRating(): void
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        $count = $approvedReviews->count();
        $avg = $count > 0 ? round((float) $approvedReviews->avg('rating'), 1) : 0.0;

        $this->update([
            'rating' => $avg,
            'reviews_count' => $count,
        ]);
    }
}
