<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

/**
 * Validates the body of PATCH /users/{user}: only the fields sent are checked.
 */
class UpdateUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'data.attributes.isManager' => 'sometimes|boolean',
            'data.attributes.password' => 'sometimes|string|min:8',
        ];
    }
}