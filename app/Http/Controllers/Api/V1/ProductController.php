<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ProductFilter;
use App\Http\Resources\V1\ProductResource;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\ReplaceProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Models\Product;

class ProductController extends APIController
{
    /**
     * GET /api/v1/products
     * Returns a paginated list (15 per page). Each product is transformed by ProductResource.
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
     * POST /api/v1/products
     * Creates a product, but only if the policy allows this user to do so.
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
        // Ask ProductPolicy::update() (checks the ability stored in the token)
        if ($this->isAble('update', $product)) {
            $product->update($request->mappedAttributes());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }
}