<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResultResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'attempt' => $this->attempt,
            'score' => $this->score,
            'percent' => $this->percent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
