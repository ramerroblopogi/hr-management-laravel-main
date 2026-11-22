<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

/**
 * Validates the body of POST /products and POST /users/{user}/products.
 */
class StoreProductRequest extends BaseProductRequest
{
    /**
     * Who may send this request is decided later by ProductPolicy (Step 19),
     * so this class always allows it.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a product.
     */
    public function rules(): array
    {
        // Where the author id comes from: the body on /products,
        // or the URL (merged into "user" below) on /users/{user}/products
        $userIdAttr = $this->routeIs('products.store')
            ? 'data.relationships.user.data.id'
            : 'user';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.product' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $userIdAttr => 'required|integer|exists:users,id',
        ];

        // A user who may only create their own products must be the author:
        // size:5 means the id must equal 5, the logged-in user's id.
        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnProduct)) {
            $rules[$userIdAttr] .= '|size:' . $user->id;
        }

        return $rules;
    }

    /**
     * Runs before validation. On /users/{user}/products the author id is in the URL,
     * so we copy it into a "user" field that the rules above can validate.
     */
    protected function prepareForValidation(): void
    {
        if ($this->routeIs('users.products.store')) {
            $this->merge([
                'user' => $this->route('user')->getKey(),
            ]);
        }
    }
}