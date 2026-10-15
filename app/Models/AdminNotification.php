<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AdminNotification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'order_id',
        'order_number',
        'customer_name',
        'total_amount',
        'title',
        'message',
        'data',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the associated order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to retrieve the most recent notifications.
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest('id')->take($limit);
    }

    /**
     * Check if the notification has been marked as read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): bool
    {
        if (!$this->isRead()) {
            $this->read_at = now();
            return $this->save();
        }

        return true;
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): bool
    {
        if ($this->isRead()) {
            $this->read_at = null;
            return $this->save();
        }

        return true;
    }

    /**
     * Safely create an admin notification for a successfully committed order.
     * Uses firstOrCreate with unique (order_id, type) to guarantee idempotency and prevent duplicates.
     */
    public static function createForNewOrder(Order $order): ?self
    {
        try {
            if (!Schema::hasTable('admin_notifications')) {
                return null;
            }

            $itemsCount = $order->relationLoaded('items')
                ? $order->items->sum('quantity')
                : $order->items()->sum('quantity');

            $title = 'New Order Received';
            $message = "Customer {$order->customer_name} placed order {$order->order_number} for $" . number_format((float) $order->total_amount, 2);

            $payload = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'total_amount' => (float) $order->total_amount,
                'items_count' => (int) $itemsCount,
                'payment_method' => $order->payment_method,
                'placed_at' => ($order->created_at ?? now())->toIso8601String(),
            ];

            return self::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'type' => 'new_order',
                ],
                [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'title' => $title,
                    'message' => $message,
                    'data' => $payload,
                    'read_at' => null,
                ]
            );
        } catch (\Throwable $e) {
            Log::warning("Failed to create admin notification for order {$order->order_number}: " . $e->getMessage(), [
                'exception' => $e,
                'order_id' => $order->id,
            ]);

            return null;
        }
    }
}
