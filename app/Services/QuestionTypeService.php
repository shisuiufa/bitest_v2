<?php

namespace App\Services;

use App\Models\QuestionType;
use Illuminate\Database\Eloquent\Collection;

class QuestionTypeService
{
    public function getListTypes(): Collection
    {
        return QuestionType::all();
    }
}
