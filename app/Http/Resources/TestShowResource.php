<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestShowResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'author_id ' => $this->author_id,
            'time_complete' => $this->time_complete,
            'attempts' => $this->attempts,
            'user_result' => new UserResultResource($this->lastTestUser()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
