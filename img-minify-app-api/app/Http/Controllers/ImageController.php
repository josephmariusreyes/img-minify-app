<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OptimizeImageRequest; 
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Endpoint;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    #[Endpoint(method: 'POST')]
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
        //Validated the files received

        //Create an ID based from UTC time

        //Create record in DB
        //ID
        //Status
        //Email
        //CreatedDate

        //Upload the files in server memory, put it in inside folder named in the generated ID

        //Run a background que to optimize the image,

        //in the payload of the que include the generatedID for reference
        
        //Background que will be the one to optimize the image

        return response()->json([
            'success' => true,
            'id' => 'Lorem ipsum',
            'message' => ''
        ], 200);
    }
    
    #[Endpoint(method: 'GET')]
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

    #[Endpoint(method: 'GET')]
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
        $path = "optimized/{$id}/output.zip";

        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::download($path);
    }
}
