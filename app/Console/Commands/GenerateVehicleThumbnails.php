<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VehicleImage;
use App\Helpers\ImageHelper;

class GenerateVehicleThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:generate-thumbnails 
                            {--force : Force regenerate thumbnails even if they exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnails for vehicle images that don\'t have one yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting thumbnail generation...');
        $this->newLine();

        // Get images without thumbnails
        $query = VehicleImage::whereNull('thumbnail_path')
            ->orWhere('thumbnail_path', '');
        
        if ($this->option('force')) {
            $query = VehicleImage::query();
            $this->warn('⚠️  Force mode: Will regenerate all thumbnails');
        }

        $images = $query->get();
        $total = $images->count();

        if ($total === 0) {
            $this->info('✅ All vehicle images already have thumbnails!');
            return Command::SUCCESS;
        }

        $this->info("Found {$total} image(s) to process");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($images as $image) {
            try {
                if (!$image->image_path) {
                    $failed++;
                    $errors[] = "Image ID {$image->id}: No image path";
                    $bar->advance();
                    continue;
                }

                // Create thumbnail
                $thumbnailPath = ImageHelper::createThumbnail($image->image_path);

                if ($thumbnailPath) {
                    $image->update(['thumbnail_path' => $thumbnailPath]);
                    $success++;
                } else {
                    $failed++;
                    $errors[] = "Image ID {$image->id}: Failed to create thumbnail";
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Image ID {$image->id}: " . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Success', $success],
                ['❌ Failed', $failed],
                ['📦 Total', $total],
            ]
        );

        if ($failed > 0 && count($errors) > 0) {
            $this->newLine();
            $this->warn('⚠️  Errors:');
            foreach (array_slice($errors, 0, 10) as $error) {
                $this->line("  - {$error}");
            }
            if (count($errors) > 10) {
                $this->line("  ... and " . (count($errors) - 10) . " more errors");
            }
        }

        $this->newLine();
        $this->info('✨ Thumbnail generation completed!');

        return Command::SUCCESS;
    }
}
