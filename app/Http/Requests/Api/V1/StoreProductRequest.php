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
     * Validation rules for creating a product (deny by default).
     */
    public function rules(): array
    {
        // Where the author id comes from: the body on /products,
        // or the URL (merged into "user" below) on /users/{user}/products
        $userIdAttr = $this->routeIs('products.store')
            ? 'data.relationships.user.data.id'
            : 'user';

        $user = $this->user();
        $userRule = 'required|integer|exists:users,id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.product' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            // Default: the author must be the logged-in user
            $userIdAttr => $userRule . '|size:' . $user->id,
        ];

        // Only the broad ability lifts the restriction (managers)
        if ($user->tokenCan(Abilities::CreateProduct)) {
            $rules[$userIdAttr] = $userRule;
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