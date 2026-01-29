<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function optimizeImage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => ''
        ], 200);
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
