<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResultResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */

    public function toArray(Request $request): array
    {
        $questionType = $request->input('questionType');

        if ($questionType === 'open') {
            $answers = $this->openAnswers;
        } elseif ($questionType === 'close') {
            $answers = $this->closeAnswers;
        } else {
            $answers = $this->answers;
        }

        return [
            'id' => $this->id,
            'title' => $this->test->title,
            'desc' => $this->test->desc,
            'image' => $this->test->image,
            'attempt' => $this->attempt,
            'score' => $this->score,
            'percent' => $this->percent,
            'status' => $this->status,
            'questionCounter' => $this->questions()->count(),
            'answerCounter' => $this->answers()->count(),
            'answers' => AnswersResource::collection($answers),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
