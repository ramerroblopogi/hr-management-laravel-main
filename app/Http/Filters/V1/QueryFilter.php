<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Base class for every filter. It turns query-string parameters into query conditions.
 * Example: ?status=A calls the status('A') method of the child filter class.
 */
abstract class QueryFilter
{
    // The Eloquent query being built (set in apply())
    protected Builder $builder;

    // The incoming request, which holds the query-string parameters
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Loop over the query parameters and call the matching method (if it exists).
     * Parameters without a method are ignored.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            // Skip "apply" and names starting with __ so clients cannot call internal methods
            if ($key !== 'apply' && ! str_starts_with($key, '__') && method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }
}
