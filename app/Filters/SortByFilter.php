<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SortByFilter implements Filter
{

    public function __invoke(Builder $query, mixed $value, string $property)
    {
        switch ($value) {
            case 'new':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'name':
                $query->orderBy('title', 'DESC');
                break;
            case 'author':
                $query->join('users', 'tests.author_id', '=', 'users.id')
                    ->orderBy('users.last_name', 'DESC');
                break;
            default:
                break;
        }

        return $query;
    }
}
