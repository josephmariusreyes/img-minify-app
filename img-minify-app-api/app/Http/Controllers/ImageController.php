<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     title="Image Optimization API",
 *     version="1.0.0",
 *     description="API for optimizing and managing images. Upload images to optimize them and retrieve information about previously optimized images.",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="API Support Team"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Images",
 *     description="Image optimization and management endpoints"
 * )
 */
class ImageController extends Controller
{
  /**
     * @OA\Post(
     *     path="/api/optimizeImage",
     *     operationId="optimizeImage",
     *     tags={"Images"},
     *     summary="Optimize uploaded image",
     *     description="Upload and optimize an image file",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"image"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Image file to optimize (jpg, png, gif, webp)"
     *                 ),
     *                 @OA\Property(
     *                     property="quality",
     *                     type="integer",
     *                     description="Optimization quality (1-100)",
     *                     example=80,
     *                     minimum=1,
     *                     maximum=100
     *                 ),
     *                 @OA\Property(
     *                     property="max_width",
     *                     type="integer",
     *                     description="Maximum width in pixels",
     *                     example=1920
     *                 ),
     *                 @OA\Property(
     *                     property="max_height",
     *                     type="integer",
     *                     description="Maximum height in pixels",
     *                     example=1080
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image optimized successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Image optimized successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="original_size", type="integer", example=1048576, description="Original file size in bytes"),
     *                 @OA\Property(property="optimized_size", type="integer", example=524288, description="Optimized file size in bytes"),
     *                 @OA\Property(property="savings", type="string", example="50%", description="Size reduction percentage"),
     *                 @OA\Property(property="url", type="string", example="https://example.com/images/optimized/image.jpg", description="URL of optimized image"),
     *                 @OA\Property(property="filename", type="string", example="optimized-image-123.jpg")
     *             )
     *         )
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

    public function getOptimizedImage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => ''
        ], 200);
    }

}
