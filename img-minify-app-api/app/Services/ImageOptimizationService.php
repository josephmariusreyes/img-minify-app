<?php

namespace App\Services;

use App\Services\Contracts\ImageOptimizationServiceInterface;
use App\Models\Upload;
use App\Enums\upload_status;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService implements ImageOptimizationServiceInterface
{
    /**
     * Upload images to storage
     * 
     * @param array $files Array of uploaded files from request
     * @return string Returns the generated request ID
     */
    public function uploadImages(array $files, string $email): string
    {
        // Create upload record first to get the upload_id
        $upload = Upload::create([
            'email' => $email,
            'upload_status' => upload_status::Pending->value,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'upload_metadata' => [],
        ]);

        $upload_id = $upload->id;

        // Process and store files, then update metadata
        $metadata = $this->processImageFiles($files, $upload_id);
        $upload->update(['upload_metadata' => $metadata]);
        
        return (string) $upload_id;
    }

    /**
     * Optimize uploaded images
     * 
     * @param string $requestId Unique identifier for the images to optimize
     * @param array $options Optimization options (quality, format, dimensions, etc.)
     * @return array Returns optimization results and statistics
     */
    public function optimizeImages(string $requestId, array $options = []): array
    {
        // TODO: Retrieve original images from storage using requestId
        
        // TODO: Apply image optimization algorithms (compression, resizing, format conversion)
        
        // TODO: Save optimized images in a separate location
        
        // TODO: Calculate and track size reduction statistics
        
        // TODO: Update database record with optimization status and results
        
        // TODO: Handle different image formats (jpg, png, webp, etc.)
        
        return [];
    }

    /**
     * Download optimized images
     * 
     * @param string $requestId Unique identifier for the images to download
     * @return mixed Returns download response (zip file or single file)
     */
    public function downloadImages(string $requestId)
    {
        // TODO: Verify requestId exists and images are ready
        
        // TODO: Check if optimization is completed
        
        // TODO: Create a zip archive of all optimized images
        
        // TODO: Generate temporary download URL or stream the file
        
        // TODO: Set appropriate headers for file download
        
        // TODO: Clean up temporary files after download
        
        // TODO: Handle cases where only one image exists (direct download vs zip)
        
        return null;
    }

    /**
     * Process image files and generate metadata
     * 
     * @param array $files Array of uploaded files
     * @param int $upload_id The upload ID for folder structure
     * @return array Returns metadata for each file
     */
    private function processImageFiles(array $files, int $upload_id): array
    {
        $metadata = [];
        $uploadPath = "uploads/{$upload_id}";
        
        foreach ($files as $index => $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            
            // Store the file in storage/app/private/uploads/{upload_id}
            $storedPath = Storage::disk('local')->putFileAs(
                $uploadPath,
                $file,
                $originalName
            );
            
            $key = 'img-' . ($index + 1);
            $metadata[$key] = [
                'originalName' => $originalName,
                'fileName' => $fileName,
                'extension' => $extension,
                'storagePath' => $storedPath,
                'origSize' => $file->getSize(),
                'optimizedSize' => 0,
                'mimeType' => $file->getMimeType(),
            ];
        }
        
        return $metadata;
    }
}
