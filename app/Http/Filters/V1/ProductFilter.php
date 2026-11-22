<?php

namespace App\Http\Filters\V1;

/**
 * The filters allowed on the products endpoints.
 */
class ProductFilter extends QueryFilter
{
    /**
     * ?createdAt=2026-08-19              one day
     * ?createdAt=2026-08-01,2026-08-31   a date range (from,to)
     */
    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    /**
     * ?updatedAt=... works exactly like createdAt, on the updated_at column.
     */
    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    /**
     * ?include=author loads the author. Only relationship names on the
     * allowed list are accepted, so clients cannot request anything else.
     */
    public function include($value)
    {
        $allowed = array_intersect(explode(',', $value), ['author']);

        return $this->builder->with($allowed);
    }

    /**
     * ?status=A   or   ?status=A,H   (one or several statuses)
     */
    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    /**
     * ?product=*phone*   (the * wildcard becomes SQL's % in a LIKE search)
     */
    public function product($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('product', 'like', $likeStr);
    }
}
