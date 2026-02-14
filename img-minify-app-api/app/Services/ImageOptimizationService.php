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
     * Optimize images and create a zip file
     * 
     * @param int $uploadId The upload ID
     * @return array Returns the result with zip file path and statistics
     */
    public function optimizeImages(string $uploadId, array $options = []): array
    {
        // Define paths
        $sourcePath = storage_path("app/private/uploads/{$uploadId}");
        $publicPath = storage_path("app/public/{$uploadId}");
        
        // Check if source directory exists
        if (!is_dir($sourcePath)) {
            return [
                'success' => false,
                'message' => 'Upload folder not found',
            ];
        }

        // Create public directory if it doesn't exist
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Get all image files from the source directory
        $imageFiles = glob($sourcePath . '/*.*');
        $imageFiles = array_filter($imageFiles, function($file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        if (empty($imageFiles)) {
            return [
                'success' => false,
                'message' => 'No images found in upload folder',
            ];
        }

        // Create temporary directory for optimized images
        $tempDir = storage_path("app/temp/optimized_{$uploadId}");
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $totalOriginalSize = 0;
        $totalOptimizedSize = 0;

        $uploadData = Upload::findOrFail($uploadId);
        $upload_metadata = json_decode($uploadData['upload_metadata'], true);

        // Optimize each image
        foreach ($imageFiles as $imagePath) {
            $filename = basename($imagePath);
            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            $outputPath = $tempDir . '/' . $filename;

            $originalSize = filesize($imagePath);
            $totalOriginalSize += $originalSize;

            // Optimize the image
            $optimized = $this->optimizeImage($imagePath, $outputPath, $extension);

            if ($optimized) {
                $optimizedSize = filesize($outputPath);
                $totalOptimizedSize += $optimizedSize;
                
                // Update the corresponding entry in upload_metadata
                foreach ($upload_metadata as $index => $metadata) {
                    if ($metadata['originalName'] === $filename) {
                        $upload_metadata[$index]['optimizedSize'] = $optimizedSize;
                        break;
                    }
                }
            }
        }

        // Create zip file with current date and time
        $zipFilename = date('Y-m-d_H-i-s') . '.zip';
        $zipPath = $publicPath . '/' . $zipFilename;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            // Add all optimized images to zip
            foreach (glob($tempDir . '/*.*') as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        } else {
            // Clean up temp directory
            $this->deleteDirectory($tempDir);
            
            return [
                'success' => false,
                'message' => 'Failed to create zip file',
            ];
        }

        // Clean up temporary directory
        $this->deleteDirectory($tempDir);

        // Update upload status
        $uploadData->update([
            'upload_status' => upload_status::Active->value,
            'updated_at' => now()->toDateTimeString(),
            'upload_metadata' => $upload_metadata,
        ]);

        return [
            'success' => true,
            'message' => 'Images optimized successfully',
            'zipFile' => $zipFilename,
            'zipPath' => "public/{$uploadId}/{$zipFilename}"
        ];
    }

    /**
     * Optimize a single image file
     * 
     * @param string $inputPath Input image path
     * @param string $outputPath Output image path
     * @param string $extension File extension
     * @return bool Success status
     */
    private function optimizeImage(string $inputPath, string $outputPath, string $extension): bool
    {
        try {
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($inputPath);
                    if ($image) {
                        // Save with 85% quality for good balance between size and quality
                        imagejpeg($image, $outputPath, 85);
                        imagedestroy($image);
                        return true;
                    }
                    break;

                case 'png':
                    $image = imagecreatefrompng($inputPath);
                    if ($image) {
                        // Enable compression, level 9 (maximum compression)
                        imagepng($image, $outputPath, 9);
                        imagedestroy($image);
                        return true;
                    }
                    break;

                case 'gif':
                    $image = imagecreatefromgif($inputPath);
                    if ($image) {
                        imagegif($image, $outputPath);
                        imagedestroy($image);
                        return true;
                    }
                    break;

                case 'webp':
                    $image = imagecreatefromwebp($inputPath);
                    if ($image) {
                        // Save with 85% quality
                        imagewebp($image, $outputPath, 85);
                        imagedestroy($image);
                        return true;
                    }
                    break;

                default:
                    // Unsupported format, just copy the file
                    copy($inputPath, $outputPath);
                    return true;
            }
        } catch (\Exception $e) {
            // If optimization fails, copy the original file
            copy($inputPath, $outputPath);
            return true;
        }

        return false;
    }

    /**
     * Recursively delete a directory and its contents
     * 
     * @param string $dir Directory path
     * @return bool Success status
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
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
            
            $metadata[] = [
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
