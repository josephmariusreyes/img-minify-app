<?php

namespace App\Services\Contracts;

interface ImageOptimizationServiceInterface
{
    /**
     * Upload images to storage
     * 
     * @param array $images Array of uploaded image files
     * @param string $requestId Unique identifier for this upload request
     * @return array Returns information about uploaded images
     * 
     * TODO: Implement the following logic:
     * - Validate image files (type, size, format)
     * - Generate unique filenames for each image
     * - Create a folder structure based on requestId
     * - Store original images in the designated folder
     * - Return paths and metadata of uploaded images
     */
    public function uploadImages(array $images, string $requestId): array;

    /**
     * Optimize uploaded images
     * 
     * @param string $requestId Unique identifier for the images to optimize
     * @param array $options Optimization options (quality, format, dimensions, etc.)
     * @return array Returns optimization results and statistics
     * 
     * TODO: Implement the following logic:
     * - Retrieve original images from storage using requestId
     * - Apply image optimization algorithms (compression, resizing, format conversion)
     * - Save optimized images in a separate location
     * - Calculate and track size reduction statistics
     * - Update database record with optimization status and results
     * - Handle different image formats (jpg, png, webp, etc.)
     */
    public function optimizeImages(string $requestId, array $options = []): array;

    /**
     * Download optimized images
     * 
     * @param string $requestId Unique identifier for the images to download
     * @return mixed Returns download response (zip file or single file)
     * 
     * TODO: Implement the following logic:
     * - Verify requestId exists and images are ready
     * - Check if optimization is completed
     * - Create a zip archive of all optimized images
     * - Generate temporary download URL or stream the file
     * - Set appropriate headers for file download
     * - Clean up temporary files after download
     * - Handle cases where only one image exists (direct download vs zip)
     */
    public function downloadImages(string $requestId);
}
