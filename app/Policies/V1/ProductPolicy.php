<?php

namespace App\Policies\V1;

use App\Models\Product;
use App\Models\User;

/**
 * Authorization rules for products. Each method returns true (allowed) or false (denied).
 */
class ProductPolicy
{
    /**
     * May this user update this product? Only its owner may (for now).
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }
}
