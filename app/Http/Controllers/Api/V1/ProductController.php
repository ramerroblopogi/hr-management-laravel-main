<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ProductFilter;
use App\Http\Resources\V1\ProductResource;
use App\Http\Requests\Api\V1\StoreProductRequest;
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
}