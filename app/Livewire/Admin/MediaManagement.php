<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Filter and search
    public $search = '';
    public $type = '';

    // Upload
    public $uploadFile = null;
    public $uploadDescription = '';
    public $showUploadModal = false;
    public $uploadProgress = 0;

    // Edit description
    public $editingMediaId = null;
    public $editingDescription = '';
    public $showEditModal = false;

    // View media
    public $viewingMedia = null;
    public $showViewModal = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'uploadFile' => 'required|file|max:10240',
        'uploadDescription' => 'nullable|string|max:500',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function openUploadModal()
    {
        $this->showUploadModal = true;
        $this->uploadFile = null;
        $this->uploadDescription = '';
        $this->uploadProgress = 0;
        $this->dispatch('modal-opened');
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->uploadFile = null;
        $this->uploadDescription = '';
        $this->uploadProgress = 0;
        $this->dispatch('modal-closed');
    }

    public function saveFile()
    {
        try {
            $this->validate([
                'uploadFile' => 'required|file|max:10240', // Max 10MB
                'uploadDescription' => 'nullable|string|max:500',
            ], [
                'uploadFile.required' => 'File harus dipilih',
                'uploadFile.file' => 'File tidak valid',
                'uploadFile.max' => 'Ukuran file maksimal 10MB',
                'uploadDescription.max' => 'Keterangan maksimal 500 karakter',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            session()->flash('error', 'Validasi gagal: ' . implode(', ', $errors));
            return;
        }

        try {
            $file = $this->uploadFile;
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
                    \Log::warning('Failed to generate thumbnail: ' . $e->getMessage());
                }
            }

            // Create media record
            Media::create([
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'description' => $this->uploadDescription ? trim($this->uploadDescription) : null,
                'thumbnail_path' => $thumbnailPath,
            ]);

            // Reset form
            $this->uploadFile = null;
            $this->uploadDescription = '';
            
            // Flash message
            session()->flash('success', 'File berhasil diupload');
            
            // Reset pagination
            $this->resetPage();
            
            // Close modal after a short delay to ensure success message is shown
            $this->dispatch('upload-success');
            $this->showUploadModal = false;
            
        } catch (\Exception $e) {
            \Log::error('Media upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Gagal mengupload file: ' . $e->getMessage());
            // Don't close modal on error so user can see the error message
        }
    }

    public function openEditModal($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $this->editingMediaId = $mediaId;
        $this->editingDescription = $media->description ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingMediaId = null;
        $this->editingDescription = '';
    }

    public function updateDescription()
    {
        $this->validate([
            'editingDescription' => 'nullable|string|max:500',
        ]);

        $media = Media::findOrFail($this->editingMediaId);
        $media->update([
            'description' => $this->editingDescription ? trim($this->editingDescription) : null,
        ]);

        $this->closeEditModal();
        session()->flash('success', 'Keterangan berhasil diperbarui');
    }

    public function deleteMedia($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);

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

            session()->flash('success', 'File berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Media delete failed: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    public function openViewModal($mediaId)
    {
        $this->viewingMedia = Media::findOrFail($mediaId);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingMedia = null;
    }

    public function render()
    {
        $query = Media::query();

        // Search by filename or description
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('filename', 'like', "%{$this->search}%")
                  ->orWhere('original_filename', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Filter by file type
        if ($this->type) {
            if ($this->type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($this->type === 'document') {
                $query->where(function ($q) {
                    $q->where('mime_type', 'like', 'application/%')
                      ->orWhere('mime_type', 'like', 'text/%');
                });
            } elseif ($this->type === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            }
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(24);

        return view('livewire.admin.media-management', compact('media'));
    }
}
