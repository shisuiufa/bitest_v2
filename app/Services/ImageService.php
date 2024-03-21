<?php

namespace App\Services;

use App\Models\Access;
use App\Models\Option;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ImageService
{
    public function uploadImage($image)
    {
        return $image->store('uploads', 'public');
    }
}
