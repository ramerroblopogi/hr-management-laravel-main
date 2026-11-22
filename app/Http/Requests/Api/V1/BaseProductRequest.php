<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared by every product request (store, replace, update).
 */
class BaseProductRequest extends FormRequest
{
    /**
     * Converts the nested JSON:API payload into database columns.
     * Only keys that were actually sent are returned, which is what makes PATCH work.
     *
     * Example: data.attributes.product  ->  product
     */
    public function mappedAttributes(array $otherAttributes = []): array
    {
        // JSON path in the request  =>  column in the products table
        $attributeMap = array_merge([
            'data.attributes.product' => 'product',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relationships.user.data.id' => 'user_id',
        ], $otherAttributes);

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            // Keep the value only if the client sent that field
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    /**
     * Custom validation messages. The key format is "field.rule".
     */
    public function messages(): array
    {
        return [
            'data.attributes.status.in' => 'The data.attributes.status value is invalid. Please use A, C, H, or X.',
        ];
    }
}
