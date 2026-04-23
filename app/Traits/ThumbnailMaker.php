<?php

namespace App\Traits;

use App\Models\File;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Storage;
use Pawlox\VideoThumbnail\VideoThumbnail;

trait ThumbnailMaker
{
    /**
     * Make thumbnail for videos when uploaded
     * @param $file_name
     * @param $file_type
     * @param $model
     * @param $file
     * @param null $model_id
     * @return void
     */
    public function makeThumbnailForVideos($file_name, $file_type, $model, $file, $model_id = null): void
    {
        $thumbs_path = Storage::disk('public')->path('files/thumbs');
        if (!file_exists($thumbs_path)) {
            mkdir($thumbs_path, 0755, true);
        }

        $thumbnail_name = 'thumb_' . date('Ymd') . '-' . $file_name . '.jpg';
        $thumbnail_path = 'files/thumbs/' . $thumbnail_name;

        try {
            // Try different path formats
            $videoPath = Storage::disk('public')->path($file);

            // If the above doesn't work, try the direct path
            if (!file_exists($videoPath)) {
                $videoPath = storage_path('app/public/' . $file);
            }

            // Check if video file exists
            if (!file_exists($videoPath)) {
                \Log::error('Video file not found at: ' . $videoPath);
                \Log::error('File parameter: ' . $file);
                \Log::error('Storage path: ' . Storage::disk('public')->path($file));
                \Log::error('Direct path: ' . storage_path('app/public/' . $file));
                return;
            }

            \Log::info('Creating thumbnail for video: ' . $videoPath);
            \Log::info('Video file size: ' . filesize($videoPath) . ' bytes');

            $ffprobe = FFProbe::create();
            $videoDimensions = $ffprobe->streams($videoPath)
                ->videos()
                ->first()
                ->getDimensions();

            $originalWidth = $videoDimensions->getWidth();
            $originalHeight = $videoDimensions->getHeight();

            $thumbnail = new VideoThumbnail();
            $result = $thumbnail->createThumbnail(
                $videoPath,
                Storage::disk('public')->path('files/thumbs'),
                $thumbnail_name,
                2,
                $originalWidth,
                $originalHeight
            );

            File::create([
                'title' => $file_name,
                'type' => $file_type,
                'model' => $model,
                'model_id' => $model_id,
                'src' => Storage::url($thumbnail_path),
                'adder' => auth()->user()->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating thumbnail: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Make thumbnail for images when uploaded
     * @param $file_name
     * @param $file_type
     * @param $model
     * @param $model_id
     * @param $file
     * @return void
     */
    public function makeThumbnailForImages($file_name, $file_type, $model, $model_id, $file): void
    {
        $thumbs_path = Storage::disk('public')->path('files/thumbs');
        if (!file_exists($thumbs_path)) {
            mkdir($thumbs_path, 0755, true);
        }

        $thumbnail_name = 'thumb_' . date('Ymd') . '-' . $file_name . '.jpg';
        $thumbnail_path = 'files/thumbs/' . $thumbnail_name;

        try {
            $imagePath = Storage::disk('public')->path($file);

            // Get image info
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                \Log::error('Unable to get image info for: ' . $imagePath);
                return;
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Calculate thumbnail dimensions (max 300x300)
            $maxWidth = 300;
            $maxHeight = 300;

            if ($width > $height) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * $maxWidth / $width);
            } else {
                $newHeight = $maxHeight;
                $newWidth = floor($width * $maxHeight / $height);
            }

            // Create image resource based on mime type
            $sourceImage = null;
            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($imagePath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($imagePath);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($imagePath);
                    break;
                case 'image/webp':
                    $sourceImage = imagecreatefromwebp($imagePath);
                    break;
                default:
                    \Log::error('Unsupported image type: ' . $mimeType);
                    return;
            }

            if (!$sourceImage) {
                \Log::error('Failed to create image resource from: ' . $imagePath);
                return;
            }

            // Create thumbnail image
            $thumbnailImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG and GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($thumbnailImage, false);
                imagesavealpha($thumbnailImage, true);
                $transparent = imagecolorallocatealpha($thumbnailImage, 255, 255, 255, 127);
                imagefilledrectangle($thumbnailImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize image
            imagecopyresampled(
                $thumbnailImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $width, $height
            );

            // Save thumbnail
            $full_thumbnail_path = Storage::disk('public')->path($thumbnail_path);
            $result = imagejpeg($thumbnailImage, $full_thumbnail_path, 85);

            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($thumbnailImage);

            if ($result) {
                File::create([
                    'name' => $file_name,
                    'type' => $file_type,
                    'model' => $model,
                    'model_id' => $model_id,
                    'src' => $full_thumbnail_path,
                    'adder' => auth()->user()->id
                ]);
                \Log::info('Image thumbnail created successfully at: ' . $full_thumbnail_path);
            } else {
                \Log::error('Failed to save image thumbnail');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating image thumbnail: ' . $e->getMessage());
        }
    }
}
