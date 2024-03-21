<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Services\ImageService;

class ImageController extends Controller
{
    public function store(ImageRequest $request, ImageService $service)
    {
        $image = $request->file('image');

        $pathImage = '/' . $service->uploadImage($image);

        return response()->json(['image' => $pathImage], 201);
    }
}
