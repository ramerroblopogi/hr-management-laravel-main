<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

/**
 * Validates the body of PATCH /products/{product}: only the fields sent are checked.
 */
class UpdateProductRequest extends BaseProductRequest
{
    /**
     * Validation rules for updating a product.
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.product' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        ];

        // Users who may only update their own products cannot change the author
        if ($this->user()->tokenCan(Abilities::UpdateOwnProduct)) {
            $rules['data.relationships.user.data.id'] = 'prohibited';
        }

        return $rules;
    }
}