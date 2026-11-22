<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

/**
 * Validates the body of PATCH /products/{product}: only the fields sent are checked.
 */
class UpdateProductRequest extends BaseProductRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a product (deny by default).
     * Null-safe so Scribe can read the rules while no user is logged in.
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.product' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            // Default: nobody may change the author
            'data.relationships.user.data.id' => 'prohibited',
        ];

        // Only the broad ability lifts the restriction (managers)
        if ($this->user()?->tokenCan(Abilities::UpdateProduct)) {
            $rules['data.relationships.user.data.id'] = 'sometimes|integer|exists:users,id';
        }

        return $rules;
    }
}