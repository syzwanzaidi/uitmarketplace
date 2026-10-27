<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SOLD = 'sold';

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'location',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function locationPoint(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function hasOrders(): bool
    {
        return $this->orderItems()->exists();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('products.status', self::STATUS_ACTIVE);
    }

    /**
     * Add a "distance" column (km) from the given point using the
     * Haversine formula. The acos() argument is clamped so floating
     * point drift can never push it outside [-1, 1].
     */
    public function scopeWithDistanceFrom(Builder $query, float $lat, float $lng, ?float $radiusKm = null): Builder
    {
        $haversine = '(6371 * acos(LEAST(1, GREATEST(-1,
            cos(radians(?)) * cos(radians(locations.latitude)) *
            cos(radians(locations.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(locations.latitude))
        ))))';

        $query->join('locations', 'products.id', '=', 'locations.product_id')
            ->select('products.*')
            ->selectRaw("$haversine AS distance", [$lat, $lng, $lat]);

        if ($radiusKm !== null) {
            $query->whereRaw("$haversine <= ?", [$lat, $lng, $lat, $radiusKm]);
        }

        return $query;
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Images are stored either as "/storage/products/x.jpg" (uploads)
     * or as absolute URLs (seed data).
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image ?: asset('assets/img/placeholder.svg');
    }

    public function deleteImageFile(): void
    {
        if ($this->image && str_starts_with($this->image, '/storage/')) {
            Storage::disk('public')->delete(substr($this->image, strlen('/storage/')));
        }
    }
}
