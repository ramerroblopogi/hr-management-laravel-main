<?php

namespace App\Policies\V1;

use App\Models\Product;
use App\Models\User;
use App\Permissions\V1\Abilities;

/**
 * Authorization rules for products. Each method returns true (allowed) or false (denied).
 * The rules read the abilities stored in the user's JWT.
 */
class ProductPolicy
{
    /**
     * May this user create a product? Needs the broad or the "own" create ability.
     */
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateProduct)
            || $user->tokenCan(Abilities::CreateOwnProduct);
    }

    /**
     * May this user update this product (PATCH)?
     */
    public function update(User $user, Product $product): bool
    {
        // Broad ability: any product
        if ($user->tokenCan(Abilities::UpdateProduct)) {
            return true;
        }

        // "Own" ability: only a product the user owns
        if ($user->tokenCan(Abilities::UpdateOwnProduct)) {
            return $user->id === $product->user_id;
        }

        return false;
    }

    /**
     * May this user replace this product (PUT)? Managers only.
     */
    public function replace(User $user, Product $product): bool
    {
        return $user->tokenCan(Abilities::ReplaceProduct);
    }

    /**
     * May this user delete this product?
     */
    public function delete(User $user, Product $product): bool
    {
        // Broad ability: any product
        if ($user->tokenCan(Abilities::DeleteProduct)) {
            return true;
        }

        // "Own" ability: only a product the user owns
        if ($user->tokenCan(Abilities::DeleteOwnProduct)) {
            return $user->id === $product->user_id;
        }

        return false;
    }
}