<?php

namespace App\Permissions\V1;

use App\Models\User;

/**
 * Every ability name in one place, plus the rule that decides who receives which.
 * Format: resource:action. "own" means "only for things I own".
 */
final class Abilities
{
    // Product abilities
    public const CreateProduct = 'product:create';
    public const CreateOwnProduct = 'product:own:create';
    public const UpdateProduct = 'product:update';
    public const UpdateOwnProduct = 'product:own:update';
    public const ReplaceProduct = 'product:replace';
    public const DeleteProduct = 'product:delete';
    public const DeleteOwnProduct = 'product:own:delete';

    // User management abilities (managers only)
    public const CreateUser = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'user:delete';

    /**
     * Returns the list of abilities a user receives. The list is written into the
     * JWT at login, so the policies can read it without querying the database.
     */
    public static function getAbilities(User $user): array
    {
        // Managers may act on anything
        if ($user->is_manager) {
            return [
                self::CreateProduct,
                self::UpdateProduct,
                self::ReplaceProduct,
                self::DeleteProduct,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        }

        // Regular users may only act on their own products
        return [
            self::CreateOwnProduct,
            self::UpdateOwnProduct,
            self::DeleteOwnProduct,
        ];
    }
}
