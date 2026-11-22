<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

/**
 * Read-only view of the users who have written products (the "authors").
 */
class AuthorsController extends Controller
{
    /**
     * GET /api/v1/authors
     * Lists only users who own at least one product, 15 per page.
     */
    public function index()
    {
        return UserResource::collection(User::has('products')->paginate());
    }

    /**
     * GET /api/v1/authors/{author}
     * Returns one author (route model binding loads the User).
     */
    public function show(User $author)
    {
        return new UserResource($author);
    }
}
