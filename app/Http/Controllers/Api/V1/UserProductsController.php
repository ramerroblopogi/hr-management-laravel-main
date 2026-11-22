<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ProductFilter;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\Api\V1\ReplaceProductRequest;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;

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

    /**
     * POST /api/v1/users/{user}/products
     * Creates a product for the user named in the URL.
     */
    public function store(StoreProductRequest $request, User $user)
    {
        if ($this->isAble('store', Product::class)) {
            // ['user' => 'user_id'] turns the id taken from the URL into the user_id column
            return new ProductResource(
                Product::create($request->mappedAttributes(['user' => 'user_id']))
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * PUT /api/v1/users/{user}/products/{product}
     * Replaces the whole product (managers only).
     */
    public function replace(ReplaceProductRequest $request, User $user, Product $product)
    {
        if ($this->isAble('replace', $product)) {
            $product->update($request->mappedAttributes());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * PATCH /api/v1/users/{user}/products/{product}
     * Updates only the fields that were sent.
     */
    public function update(UpdateProductRequest $request, User $user, Product $product)
    {
        if ($this->isAble('update', $product)) {
            $product->update($request->mappedAttributes());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * DELETE /api/v1/users/{user}/products/{product}
     * Deletes the product. The scoped binding returns 404 if it is not this user's product.
     */
    public function destroy(User $user, Product $product)
    {
        if ($this->isAble('delete', $product)) {
            $product->delete();

            return $this->ok('Product successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}