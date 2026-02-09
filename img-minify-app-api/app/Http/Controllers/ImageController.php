<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OptimizeImageRequest; 
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Endpoint;
use Illuminate\Support\Facades\Storage;
use App\Services\Contracts\ImageOptimizationServiceInterface;

class ImageController extends Controller
{
    /**
     * Constructor - inject ImageOptimizationService
     */
    public function __construct(
        private ImageOptimizationServiceInterface $imageOptimizationService
    ) {}

    #[Response(
        status: 200,
        content: [
            'success' => true,
            'id' => '',
            'message' => '',
        ]
    )]
    public function optimizeImage(OptimizeImageRequest $request)
    {
        // Extract validated data
        $files = $request->validated()['files'];
        $email = $request->validated()['email'] ?? null;

        // Upload validated images and get generated ID
        $uploadId = $this->imageOptimizationService->uploadImages($files, $email);

        //Run a background que to optimize the image,
        // TODO: Use $this->imageOptimizationService->optimizeImages()

        //in the payload of the que include the generatedID for reference

        return response()->json([
            'success' => true,
            'id' => $uploadId,
            'message' => 'Images uploaded successfully. Optimization in progress.'
        ], 200);
    }
    
    #[Response(
        status: 200,
        content: [
            'success' => true,
            'message' => '',
            'data' => '',
            'id' => ''
        ]
    )]
    public function getOptimizedImage(string $id): JsonResponse
    {
        //this will be called with the unique ID

        //this method will be polled until the images are ready

        // Validate ID is not empty
        if (empty($id)) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lorem ipsum',
            'data' => '',
            'id' => $id
        ], 200);
    }

    #[Response(
        status: 200,
        content: 'application/zip',
        description: 'Returns a zip file containing the optimized images'
    )]
    #[Response(
        status: 404,
        content: [
            'message' => 'File not found'
        ]
    )]
    public function downloadOptimizedImage(string $id)
    {
        // TODO: Use $this->imageOptimizationService->downloadImages()
        
        $path = "optimized/{$id}/output.zip";

        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::download($path);
    }
}
