<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at')->orderBy('id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function scopeInvolving(Builder $query, int $userId): Builder
    {
        return $query->where(fn ($q) => $q->where('buyer_id', $userId)->orWhere('seller_id', $userId));
    }

    public function hasParticipant(User $user): bool
    {
        return $user->id === $this->buyer_id || $user->id === $this->seller_id;
    }

    public function partnerOf(User $user): ?User
    {
        return $user->id === $this->buyer_id ? $this->seller : $this->buyer;
    }
}
