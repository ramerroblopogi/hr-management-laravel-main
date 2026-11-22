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
     * Validates the body with StoreProductRequest, then creates the product.
     * Laravel answers 201 Created automatically for a newly created resource.
     */
    public function store(StoreProductRequest $request)
    {
        // mappedAttributes() converts the nested JSON into database columns
        return new ProductResource(
            Product::create($request->mappedAttributes())
        );
    }

    /**
     * DELETE /api/v1/products/{product}
     * Deletes the product. A missing product never reaches this method: Laravel answers 404.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->ok('Product successfully deleted');
    }

    /**
     * PUT /api/v1/products/{product}
     * Replaces the whole product: every field must be sent (see ReplaceProductRequest).
     */
    public function replace(ReplaceProductRequest $request, Product $product)
    {
        $product->update($request->mappedAttributes());

        return new ProductResource($product);
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