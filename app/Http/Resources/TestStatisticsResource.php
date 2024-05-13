<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestStatisticsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'users' => UserResultResource::collection($this->uniqueUsersByAttemptDesc()),
            'monthly_stats' => $this->getTestResultsByMonthChartData(),
            'passing_percentage' => $this->getTestResultsChartData(),
            'passing_duration' => $this->getTestDurationStatistics(),
        ];
    }
}
