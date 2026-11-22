<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Http\Resources\V1\ProductResource;
use App\Http\Filters\V1\ProductFilter;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\ReplaceProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;

/**
 * @group Products
 *
 * Create, read, update and delete products.
 */
class ProductController extends APIController
{
    /**
     * List products
     *
     * Returns a paginated list of products. Supports filtering, sorting and including the author.
     *
     * @queryParam status string Filter by status. Use a comma for several. Example: A,H
     * @queryParam product string Filter by name. Use * as a wildcard. Example: *phone*
     * @queryParam createdAt string A date, or a range as from,to. Example: 2026-01-01,2026-12-31
     * @queryParam sort string Sort by product, status, createdAt or updatedAt. Prefix with - for descending. Example: -createdAt
     * @queryParam include string Include related data. Example: author
     * @queryParam page integer The page number. Example: 1
     */
    public function index(ProductFilter $filters)
    {
        // Apply the filters from the URL (?status=A, ?product=*x*, ?include=author ...)
        return ProductResource::collection(
            Product::filter($filters)->paginate()
        );
    }

    /**
     * GET /api/v1/products/{product}
     * Returns one product. Laravel loads it from the URL (route model binding)
     * and answers 404 by itself when it does not exist.
     */
    public function show(Product $product)
    {
        // ?include=author: load the author of this product
        if ($this->include('author')) {
            return new ProductResource($product->load('author'));
        }

        return new ProductResource($product);
    }

    /**
     * Create a product
     *
     * Managers can assign any author. Regular users can only create products for themselves.
     */
    public function store(StoreProductRequest $request)
    {
        // Ask ProductPolicy::store() (checks the ability stored in the token)
        if ($this->isAble('store', Product::class)) {
            return new ProductResource(
                Product::create($request->mappedAttributes())
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * DELETE /api/v1/products/{product}
     * Deletes the product, but only if the policy allows it.
     */
    public function destroy(Product $product)
    {
        if ($this->isAble('delete', $product)) {
            $product->delete();

            return $this->ok('Product successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }

    /**
     * PUT /api/v1/products/{product}
     * Replaces the whole product, but only if the policy allows it.
     */
    public function replace(ReplaceProductRequest $request, Product $product)
    {
        if ($this->isAble('replace', $product)) {
            $product->update($request->mappedAttributes());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * PATCH /api/v1/products/{product}
     * Updates only the fields that were sent, but only if the policy allows it.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Ask ProductPolicy::update() (owner, or a user holding the broader ability)
        if ($this->isAble('update', $product)) {
            $product->update($request->mappedAttributes());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }
}