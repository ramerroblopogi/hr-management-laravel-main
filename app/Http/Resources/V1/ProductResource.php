<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a Product model into the JSON structure our API returns.
 */
class ProductResource extends JsonResource
{
    /**
     * Build the array that becomes the JSON response.
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'product' => $this->product,
                // Shown on the detail page only: hidden on the list endpoints
                'description' => $this->when(
                    ! $request->routeIs(['products.index', 'users.products.index']),
                    $this->description
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    // Link to the author's own endpoint
                    'links' => [
                        'self' => route('authors.show', ['author' => $this->user_id]),
                    ],
                ],
            ],
            'links' => [
                'self' => route('products.show', ['product' => $this->id]),
            ],
        ];
    }
}
