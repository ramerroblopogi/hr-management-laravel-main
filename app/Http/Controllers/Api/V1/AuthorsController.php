<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\UserResource;
use App\Models\User;

/**
 * Read-only view of the users who have written products (the "authors").
 */
class AuthorsController extends APIController
{
    /**
     * GET /api/v1/authors
     * Lists only users who own at least one product, 15 per page.
     */
    public function index()
    {
        // ?include=products: load each author's products in one extra query
        if ($this->include('products')) {
            return UserResource::collection(User::has('products')->with('products')->paginate());
        }

        return UserResource::collection(User::has('products')->paginate());
    }

    /**
     * GET /api/v1/authors/{author}
     * Returns one author (route model binding loads the User).
     */
    public function show(User $author)
    {
        // ?include=products: load this author's products
        if ($this->include('products')) {
            return new UserResource($author->load('products'));
        }

        return new UserResource($author);
    }
}
