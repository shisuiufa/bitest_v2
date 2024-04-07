<?php

namespace App\Http\Resources;

use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestPassResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $service = new TestService();

        return [
            'id' => $this->test->id,
            'test_user_id' => $this->id,
            'time' => $service->getRemainingTimeForTest($this->resource),
            'questions' => QuestionPassTestResource::collection($this->questions),
        ];
    }
}
