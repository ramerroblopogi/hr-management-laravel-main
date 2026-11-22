<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ProductResource;
use App\Models\Product;

class ProductController extends APIController
{
    /**
     * GET /api/v1/products
     * Returns a paginated list (15 per page). Each product is transformed by ProductResource.
     */
    public function index()
    {
        // ?include=author: eager load the authors in one extra query
        if ($this->include('author')) {
            return ProductResource::collection(Product::with('author')->paginate());
        }

        return ProductResource::collection(Product::paginate());
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
}
