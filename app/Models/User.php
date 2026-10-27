<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const TYPE_ADMIN = 'admin';
    public const TYPE_UITM = 'uitm';
    public const TYPE_PUBLIC = 'non_uitm';

    /** Email domains that qualify an account as a UiTM member (can sell). */
    public const UITM_DOMAINS = ['@student.uitm.edu.my', '@uitm.edu.my'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'membership_type',
        'status',
        'phone',
        'address1',
        'address2',
        'postcode',
        'city',
        'state',
        'country',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function membershipForEmail(string $email): string
    {
        return Str::endsWith(Str::lower($email), self::UITM_DOMAINS) ? self::TYPE_UITM : self::TYPE_PUBLIC;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function isAdmin(): bool
    {
        return $this->membership_type === self::TYPE_ADMIN;
    }

    public function canSell(): bool
    {
        return $this->membership_type === self::TYPE_UITM;
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getMembershipLabelAttribute(): string
    {
        return match ($this->membership_type) {
            self::TYPE_ADMIN => 'Administrator',
            self::TYPE_UITM => 'UiTM member',
            default => 'Public member',
        };
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : null;
    }

    public function getInitialsAttribute(): string
    {
        return Str::of($this->name)->explode(' ')->filter()->take(2)
            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('') ?: '?';
    }

    /** Full delivery address on one line, empty parts skipped. */
    public function getFullAddressAttribute(): string
    {
        return collect([$this->address1, $this->address2, trim($this->postcode . ' ' . $this->city), $this->state, $this->country])
            ->filter()->implode(', ');
    }

    public function hasCompleteAddress(): bool
    {
        return filled($this->phone) && filled($this->address1) && filled($this->postcode) && filled($this->city) && filled($this->state);
    }
}
