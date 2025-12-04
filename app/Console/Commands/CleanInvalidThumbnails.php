<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VehicleImage;
use Illuminate\Support\Facades\Storage;

class CleanInvalidThumbnails extends Command
{
    protected $signature = 'vehicles:clean-thumbnails';
    protected $description = 'Clean invalid thumbnail paths from database';

    public function handle()
    {
        $this->info('Cleaning invalid thumbnails...');
        
        $images = VehicleImage::whereNotNull('thumbnail_path')->get();
        $cleaned = 0;
        
        foreach ($images as $image) {
            if (!Storage::disk('public')->exists($image->thumbnail_path)) {
                $image->update(['thumbnail_path' => null]);
                $cleaned++;
                $this->line("Cleaned thumbnail for image ID: {$image->id}");
            }
        }
        
        $this->info("✅ Cleaned {$cleaned} invalid thumbnail(s)");
        
        return Command::SUCCESS;
    }
}

