<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /** Orders that represent money actually received. */
    public const SETTLED_STATUSES = [self::STATUS_PAID, self::STATUS_SHIPPED, self::STATUS_COMPLETED];

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'total',
        'status',
        'bill_code',
        'payment_ref',
        'paid_at',
        'shipped_at',
        'completed_at',
        'tracking_number',
        'courier',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getReferenceAttribute(): string
    {
        return 'ORDER-' . $this->id;
    }

    /** Accepts "12", "#12", "order-12" or "#ORDER-12". */
    public static function idFromSearch(?string $search): ?int
    {
        $digits = preg_replace('/\D/', '', (string) $search);

        return $digits === '' ? null : (int) $digits;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Awaiting payment',
            self::STATUS_PAID => 'To ship',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Payment failed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    /** Colour token used by the <x-status-badge> component. */
    public function statusTone(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'info',
            self::STATUS_SHIPPED => 'primary',
            self::STATUS_COMPLETED => 'success',
            default => 'danger',
        };
    }
}
