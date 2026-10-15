<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_subtotal',
        'max_discount',
        'usage_limit',
        'used_count',
        'eligible_categories',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_subtotal' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'eligible_categories' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Validate coupon eligibility against current cart subtotal and items.
     *
     * @param float $cartSubtotal Authoritative merchandise subtotal
     * @param string|null $errorMessage Out parameter describing why validation failed
     * @param array $items Array of cart items or order items
     * @return bool True if valid, false otherwise
     */
    public function isValidForSubtotal(float $cartSubtotal, ?string &$errorMessage = null, array $items = []): bool
    {
        // 1. Active status check
        if (!$this->is_active) {
            $errorMessage = "The coupon code \"{$this->code}\" is invalid or currently inactive.";
            return false;
        }

        $now = now();

        // 2. Start date check
        if ($this->starts_at && $now->lt($this->starts_at)) {
            $errorMessage = "The coupon code \"{$this->code}\" is not active yet (starts on " . $this->starts_at->format('M d, Y') . ").";
            return false;
        }

        // 3. Expiry date check
        if ($this->expires_at && $now->gt($this->expires_at)) {
            $errorMessage = "The coupon code \"{$this->code}\" has expired on " . $this->expires_at->format('M d, Y') . ".";
            return false;
        }

        // 4. Overall usage limit check
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            $errorMessage = "The coupon code \"{$this->code}\" has reached its maximum redemption limit.";
            return false;
        }

        // 5. Minimum subtotal requirement check
        if ($this->min_subtotal !== null && $cartSubtotal < (float) $this->min_subtotal) {
            $errorMessage = "The coupon \"{$this->code}\" requires a minimum order subtotal of $" . number_format((float) $this->min_subtotal, 2) . ". Your current subtotal is $" . number_format($cartSubtotal, 2) . ".";
            return false;
        }

        // 6. Category scope check (if coupon specifies restricted categories)
        if (!empty($this->eligible_categories) && is_array($this->eligible_categories) && !empty($items)) {
            $eligibleSubtotal = $this->calculateEligibleSubtotal($items);
            if ($eligibleSubtotal <= 0.0) {
                $categoriesList = implode(', ', $this->eligible_categories);
                $errorMessage = "The coupon \"{$this->code}\" is valid only for products in: {$categoriesList}. None of the items in your cart qualify.";
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate the monetary discount for the given subtotal and items.
     *
     * @param float $cartSubtotal Authoritative merchandise subtotal
     * @param array $items Array of cart items or order items
     * @return float Calculated discount rounded to 2 decimal places
     */
    public function calculateDiscount(float $cartSubtotal, array $items = []): float
    {
        if ($cartSubtotal <= 0.0) {
            return 0.0;
        }

        // Determine the base eligible amount
        $baseAmount = $cartSubtotal;
        if (!empty($this->eligible_categories) && is_array($this->eligible_categories) && !empty($items)) {
            $baseAmount = $this->calculateEligibleSubtotal($items);
        }

        if ($baseAmount <= 0.0) {
            return 0.0;
        }

        $discount = 0.0;

        if ($this->type === 'percentage') {
            $percentage = (float) $this->value;
            $discount = round($baseAmount * ($percentage / 100.0), 2);

            // Apply max discount cap if configured
            if ($this->max_discount !== null && (float) $this->max_discount > 0.0) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } elseif ($this->type === 'fixed') {
            $discount = (float) $this->value;
        }

        // Safeguards: discount can never exceed eligible base amount or total subtotal
        $discount = min($discount, $baseAmount);
        $discount = min($discount, $cartSubtotal);

        return max(0.0, round($discount, 2));
    }

    /**
     * Compute subtotal sum for items matching this coupon's eligible categories.
     *
     * @param array $items
     * @return float
     */
    protected function calculateEligibleSubtotal(array $items): float
    {
        $eligibleSubtotal = 0.0;
        $allowed = array_map('strtolower', $this->eligible_categories ?? []);

        foreach ($items as $item) {
            $category = $item['category'] ?? ($item['product']->category ?? null);
            if ($category && in_array(strtolower($category), $allowed, true)) {
                $lineSubtotal = (float) ($item['subtotal'] ?? ($item['item_subtotal'] ?? 0.0));
                $eligibleSubtotal += $lineSubtotal;
            }
        }

        return $eligibleSubtotal;
    }

    /**
     * Get user-friendly badge text for discount magnitude (e.g. "50% OFF" or "$10.00 OFF").
     */
    public function getDiscountBadgeAttribute(): string
    {
        if ($this->type === 'percentage') {
            return (int) $this->value . '% OFF';
        }

        return '$' . number_format((float) $this->value, 2) . ' OFF';
    }

    /**
     * Get concise human-readable description of coupon terms and limitations.
     */
    public function getTermsSummaryAttribute(): string
    {
        $parts = [];
        if ($this->type === 'percentage') {
            $parts[] = (int) $this->value . '% off';
        } else {
            $parts[] = '$' . number_format((float) $this->value, 2) . ' off';
        }

        if (!empty($this->eligible_categories) && is_array($this->eligible_categories)) {
            $parts[] = 'on ' . implode(', ', $this->eligible_categories);
        } else {
            $parts[] = 'store-wide';
        }

        $conditions = [];
        if ($this->min_subtotal && (float) $this->min_subtotal > 0) {
            $conditions[] = 'Min. order $' . number_format((float) $this->min_subtotal, 2);
        }
        if ($this->max_discount && (float) $this->max_discount > 0) {
            $conditions[] = 'Max savings $' . number_format((float) $this->max_discount, 2);
        }

        if (!empty($conditions)) {
            return implode(' ', $parts) . ' (' . implode(', ', $conditions) . ')';
        }

        return implode(' ', $parts);
    }
}
