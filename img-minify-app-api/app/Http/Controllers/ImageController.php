<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/image",
     *     operationId="",
     *     tags={""},
     *     summary="",
     *     description="",
     *     @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function optimizeImage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => ''
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/image",
     *     operationId="",
     *     tags={""},
     *     summary="",
     *     description="",
     *     @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function getOptimizedImage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => ''
        ], 200);
    }

}
