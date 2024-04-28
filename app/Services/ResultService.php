<?php

namespace App\Services;

use App\Enums\TestStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ResultService
{
    public function getResults(bool $pagination, int|null $page = 1, int|null $perPage = 10): LengthAwarePaginator|Collection
    {
        $user = auth()->user();

        $results = $user->tests()
            ->whereNot('status', TestStatus::ONGOING)
            ->orderBy('attempt');

        if ($pagination) {
            $results = $results->paginate($perPage, ['*'], 'page', $page);
        } else {
            $results = $results->get();
        }

        return $results->sortBy('attempt')->unique('id');
    }
}
