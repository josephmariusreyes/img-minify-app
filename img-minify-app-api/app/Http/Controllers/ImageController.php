<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OptimizeImageRequest; 
use App\Http\Resources\OptimizeImageResponse;

class ImageController extends Controller
{
    public function optimizeImage(OptimizeImageRequest $request)
    {
        $result = [
            'success' => true,
            'id'      => 'img_123456',
            'message' => 'Image optimized successfully',
        ];

        return new OptimizeImageResponse((object) $result);
    }

    public function getOptimizedImage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => ''
        ], 200);
    }

}
