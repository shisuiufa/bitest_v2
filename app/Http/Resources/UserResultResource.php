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
            'test_user_id' => $this->id,
            'user_id' => $this->user->id,
            'user_full_name' => $this->user->last_name . ' ' . $this->user->first_name,
            'user_avatar' => $this->user->avatar,
            'attempt' => $this->attempt,
            'score' => $this->score,
            'percent' => $this->percent,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'test_end_at' => $this->test_end_at,
        ];
    }
}
