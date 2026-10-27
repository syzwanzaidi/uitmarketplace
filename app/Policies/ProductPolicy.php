<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /** Sellers manage their own listings; sold listings are part of order history and stay frozen. */
    public function update(User $user, Product $product): bool
    {
        return $product->user_id === $user->id && $product->status !== Product::STATUS_SOLD;
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }
}
