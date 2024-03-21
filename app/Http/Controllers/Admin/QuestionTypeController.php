<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionTypeResource;
use App\Services\QuestionTypeService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionTypeController extends Controller
{
    public function index(QuestionTypeService $service): ResourceCollection
    {
        $questionTypes = $service->getListTypes();

        return QuestionTypeResource::collection($questionTypes);
    }
}
