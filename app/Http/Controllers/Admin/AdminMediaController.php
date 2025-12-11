<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminMediaController extends Controller
{
    /**
     * Display a listing of media files
     */
    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Store a newly uploaded media file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $folder = 'media_ds';
            $filePath = $folder . '/' . $filename;

            // Upload to S3
            $fileContent = file_get_contents($file->getRealPath());
            Storage::disk('s3')->put($filePath, $fileContent, 'public');

            // Generate thumbnail for images
            $thumbnailPath = null;
            if (str_starts_with($mimeType, 'image/')) {
                try {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($file->getRealPath());
                    
                    // Resize with maintain aspect ratio (max 300x300)
                    $image->scale(width: 300, height: 300);
                    
                    $thumbnailFilename = 'thumb_' . $filename;
                    $thumbnailPath = $folder . '/' . $thumbnailFilename;
                    
                    // Save thumbnail to temp file first
                    $tempThumbnail = sys_get_temp_dir() . '/' . $thumbnailFilename;
                    $image->toJpeg(85)->save($tempThumbnail);
                    
                    // Upload thumbnail to S3
                    $thumbnailContent = file_get_contents($tempThumbnail);
                    Storage::disk('s3')->put($thumbnailPath, $thumbnailContent, 'public');
                    
                    // Clean up temp file
                    @unlink($tempThumbnail);
                } catch (\Exception $e) {
                    // If thumbnail generation fails, continue without thumbnail
                    \Log::warning('Failed to generate thumbnail: ' . $e->getMessage());
                }
            }

            // Create media record
            $media = Media::create([
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'description' => $request->input('description'),
                'thumbnail_path' => $thumbnailPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload',
                'data' => [
                    'id' => $media->id,
                    'url' => $media->url,
                    'thumbnail_url' => $media->thumbnail_url,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Media upload failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update media description
     */
    public function update(Request $request, Media $media)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.media.index')
                ->withErrors($validator)
                ->with('error', 'Validasi gagal');
        }

        // Get current query parameters for redirect
        $queryParams = $request->only(['search', 'type', 'page']);
        
        // Update only description field
        $description = $request->input('description');
        $media->update([
            'description' => $description ? trim($description) : null,
        ]);

        return redirect()->route('admin.media.index', $queryParams)
            ->with('success', 'Keterangan berhasil diperbarui');
    }

    /**
     * Delete media file
     */
    public function destroy(Media $media)
    {
        try {
            // Delete from S3
            if (Storage::disk('s3')->exists($media->file_path)) {
                Storage::disk('s3')->delete($media->file_path);
            }

            // Delete thumbnail if exists
            if ($media->thumbnail_path && Storage::disk('s3')->exists($media->thumbnail_path)) {
                Storage::disk('s3')->delete($media->thumbnail_path);
            }

            // Delete record
            $media->delete();

            return redirect()->route('admin.media.index')
                ->with('success', 'File berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Media delete failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    /**
     * Get media URL
     */
    public function getUrl(Media $media)
    {
        return response()->json([
            'url' => $media->url,
        ]);
    }
}

