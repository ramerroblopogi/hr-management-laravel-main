<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a User model into the JSON structure our API returns.
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                // true for managers, false for everyone else
                'isManager' => $this->is_manager,
                // These fields appear only on the authors and users endpoints
                $this->mergeWhen($request->routeIs(['authors.*', 'users.*']), [
                    'emailVerifiedAt' => $this->email_verified_at,
                    'createdAt' => $this->created_at,
                    'updatedAt' => $this->updated_at,
                ]),
            ],
            // The products array, present only when requested with ?include=products
            'includes' => ProductResource::collection($this->whenLoaded('products')),
            // Link to this user
            'links' => [
                'self' => route('authors.show', ['author' => $this->id]),
            ],
        ];
    }
}