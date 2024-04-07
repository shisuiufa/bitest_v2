<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'image' => $this->image,
            'author_id ' => $this->author_id,
            'time_complete' => $this->time_complete,
            'attempts' => $this->attempts,
            'limit_questions' => $this->limit_questions,
            'published' => $this->published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
