<?php

namespace App\Services\Contracts;

interface ImageOptimizationServiceInterface
{
    /**
     * Upload images to storage
     * 
     * @param array $files Array of uploaded files from request
     * @return string Returns the generated request ID
     * 
     */
    public function uploadImages(array $files, string $email): string;

    /**
     * Optimize uploaded images
     * 
     * @param string $requestId Unique identifier for the images to optimize
     * @param array $options Optimization options (quality, format, dimensions, etc.)
     * @return array Returns optimization results and statistics
     * 
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
