<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ProductFilter;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Models\User;

/**
 * Products that belong to one user: /api/v1/users/{user}/products
 */
class UserProductsController extends APIController
{
    /**
     * GET /api/v1/users/{user}/products
     * Lists only this user's products. All ProductFilter parameters work here too.
     */
    public function index(User $user, ProductFilter $filters)
    {
        return ProductResource::collection(
            Product::where('user_id', $user->id)->filter($filters)->paginate()
        );
    }
}
