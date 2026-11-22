<?php

namespace App\Http\Requests\Api\V1;

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
     * Validation rules for updating a product. "sometimes" means:
     * validate the field only when it was sent.
     */
    public function rules(): array
    {
        return [
            'data.attributes.product' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        ];
    }
}