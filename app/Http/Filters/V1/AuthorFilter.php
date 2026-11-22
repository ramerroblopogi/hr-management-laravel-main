<?php

namespace App\Http\Filters\V1;

/**
 * The filters and sort columns allowed on the authors and users endpoints.
 */
class AuthorFilter extends QueryFilter
{
    // The columns clients may sort users by
    protected array $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    // ?id=1,2,3  (one or several ids)
    public function id($value)
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    // ?name=*john*  (wildcard search)
    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likeStr);
    }

    // ?email=*@example.com  (wildcard search)
    public function email($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('email', 'like', $likeStr);
    }

    // ?createdAt=2026-08-19  or  ?createdAt=from,to
    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    // ?updatedAt=2026-08-19  or  ?updatedAt=from,to
    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    // ?include=products loads each user's products (allowed relationships only)
    public function include($value)
    {
        $allowed = array_intersect(explode(',', $value), ['products']);

        return $this->builder->with($allowed);
    }
}