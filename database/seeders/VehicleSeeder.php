<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use App\Models\VehicleImage;
use App\Models\User;
use App\Helpers\ImageHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories and brands
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::all();
        $members = User::where('role', 'member')->get();
        
        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->warn('Please run VehicleCategorySeeder and VehicleBrandSeeder first!');
            return;
        }

        // Get all images from public/kendaraan
        $imageDir = public_path('kendaraan');
        $availableImages = [];
        
        if (File::exists($imageDir)) {
            $files = File::files($imageDir);
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $availableImages[] = $file->getFilename();
                }
            }
        }

        if (empty($availableImages)) {
            $this->command->warn('No images found in public/kendaraan folder!');
            return;
        }

        // Vehicle data templates
        $vehicleTemplates = [
            [
                'name' => 'Toyota Avanza G',
                'model' => 'Avanza',
                'category' => 'Mobil MPV',
                'brand' => 'Toyota',
                'year' => 2023,
                'color' => 'Silver',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 7,
                'price_per_day' => 300000,
                'price_per_day_no_driver' => 250000,
                'machine_cc' => 1300,
                'description' => 'MPV keluarga yang nyaman dengan kapasitas 7 penumpang. Cocok untuk perjalanan keluarga atau rombongan kecil.',
                'features' => ['AC', 'Audio System', 'USB Port', 'Bluetooth', 'Kamera Mundur'],
            ],
            [
                'name' => 'Honda Brio',
                'model' => 'Brio',
                'category' => 'Mobil Sedan',
                'brand' => 'Honda',
                'year' => 2024,
                'color' => 'White',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 5,
                'price_per_day' => 200000,
                'price_per_day_no_driver' => 150000,
                'machine_cc' => 1200,
                'description' => 'City car yang efisien dan nyaman untuk perjalanan dalam kota.',
                'features' => ['AC', 'Audio System', 'USB Port'],
            ],
            [
                'name' => 'Daihatsu Sigra',
                'model' => 'Sigra',
                'category' => 'Mobil MPV',
                'brand' => 'Daihatsu',
                'year' => 2023,
                'color' => 'Red',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 7,
                'price_per_day' => 280000,
                'price_per_day_no_driver' => 230000,
                'machine_cc' => 1200,
                'description' => 'MPV kompak dengan desain modern dan efisiensi bahan bakar yang baik.',
                'features' => ['AC', 'Audio System', 'Bluetooth', 'Kamera Mundur'],
            ],
            [
                'name' => 'Toyota Fortuner',
                'model' => 'Fortuner',
                'category' => 'Mobil SUV',
                'brand' => 'Toyota',
                'year' => 2023,
                'color' => 'Black',
                'fuel_type' => 'Solar',
                'transmission' => 'Automatic',
                'seats' => 7,
                'price_per_day' => 650000,
                'price_per_day_no_driver' => 550000,
                'machine_cc' => 2400,
                'description' => 'SUV premium dengan performa tangguh dan kenyamanan tinggi. Cocok untuk perjalanan off-road maupun dalam kota.',
                'features' => ['AC', 'Leather Seats', 'GPS', '4WD', 'Parkir Otomatis', 'Sunroof'],
            ],
            [
                'name' => 'Toyota Alphard',
                'model' => 'Alphard',
                'category' => 'Mobil MPV',
                'brand' => 'Toyota',
                'year' => 2024,
                'color' => 'White',
                'fuel_type' => 'Bensin',
                'transmission' => 'Automatic',
                'seats' => 7,
                'price_per_day' => 1200000,
                'price_per_day_no_driver' => 1000000,
                'machine_cc' => 3500,
                'description' => 'MPV mewah dengan fasilitas premium dan kenyamanan maksimal untuk perjalanan jarak jauh.',
                'features' => ['AC', 'Leather Seats', 'GPS', 'Bluetooth', 'Sunroof', 'Third Row Seats', 'Keyless Entry', 'Push Start'],
            ],
            [
                'name' => 'Toyota Innova Reborn',
                'model' => 'Innova',
                'category' => 'Mobil MPV',
                'brand' => 'Toyota',
                'year' => 2023,
                'color' => 'Silver',
                'fuel_type' => 'Solar',
                'transmission' => 'Manual',
                'seats' => 7,
                'price_per_day' => 400000,
                'price_per_day_no_driver' => 350000,
                'machine_cc' => 2400,
                'description' => 'MPV andalan keluarga dengan performa tangguh dan kapasitas besar.',
                'features' => ['AC', 'Audio System', 'USB Port', 'Bluetooth', 'Kamera Mundur'],
            ],
            [
                'name' => 'Daihatsu Xenia',
                'model' => 'Xenia',
                'category' => 'Mobil MPV',
                'brand' => 'Daihatsu',
                'year' => 2022,
                'color' => 'Blue',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 7,
                'price_per_day' => 280000,
                'price_per_day_no_driver' => 230000,
                'machine_cc' => 1300,
                'description' => 'MPV praktis dengan harga terjangkau dan efisiensi bahan bakar yang baik.',
                'features' => ['AC', 'Audio System', 'USB Port'],
            ],
            [
                'name' => 'Toyota Agya',
                'model' => 'Agya',
                'category' => 'Mobil Sedan',
                'brand' => 'Toyota',
                'year' => 2024,
                'color' => 'Red',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 5,
                'price_per_day' => 200000,
                'price_per_day_no_driver' => 150000,
                'machine_cc' => 1000,
                'description' => 'City car yang compact dan efisien, cocok untuk perjalanan dalam kota.',
                'features' => ['AC', 'Audio System', 'USB Port'],
            ],
            [
                'name' => 'Daihatsu Ayla',
                'model' => 'Ayla',
                'category' => 'Mobil Sedan',
                'brand' => 'Daihatsu',
                'year' => 2023,
                'color' => 'White',
                'fuel_type' => 'Bensin',
                'transmission' => 'Manual',
                'seats' => 5,
                'price_per_day' => 180000,
                'price_per_day_no_driver' => 130000,
                'machine_cc' => 1000,
                'description' => 'City car yang praktis dan ekonomis untuk kebutuhan sehari-hari.',
                'features' => ['AC', 'Audio System'],
            ],
            [
                'name' => 'Mitsubishi Pajero Sport',
                'model' => 'Pajero Sport',
                'category' => 'Mobil SUV',
                'brand' => 'Mitsubishi',
                'year' => 2023,
                'color' => 'Dark Grey',
                'fuel_type' => 'Solar',
                'transmission' => 'Automatic',
                'seats' => 7,
                'price_per_day' => 700000,
                'price_per_day_no_driver' => 600000,
                'machine_cc' => 2400,
                'description' => 'SUV tangguh dengan kemampuan off-road yang handal dan kenyamanan premium.',
                'features' => ['AC', 'Leather Seats', 'GPS', '4WD', 'Parkir Otomatis', 'Cruise Control'],
            ],
            [
                'name' => 'Toyota Hiace Commuter',
                'model' => 'Hiace',
                'category' => 'Bus Medium',
                'brand' => 'Toyota',
                'year' => 2022,
                'color' => 'White',
                'fuel_type' => 'Solar',
                'transmission' => 'Manual',
                'seats' => 15,
                'price_per_day' => 800000,
                'price_per_day_no_driver' => 700000,
                'machine_cc' => 2800,
                'description' => 'Bus medium dengan kapasitas 15 penumpang. Ideal untuk perjalanan grup atau wisata.',
                'features' => ['AC', 'Audio System', 'Comfortable Seats', 'Luggage Space'],
            ],
        ];

        // Generate 25 vehicles
        $colors = ['White', 'Black', 'Silver', 'Red', 'Blue', 'Grey', 'Dark Grey', 'Brown'];
        $fuelTypes = ['Bensin', 'Solar'];
        $transmissions = ['Manual', 'Automatic', 'CVT'];
        $commonFeatures = [
            ['AC', 'Audio System'],
            ['AC', 'Audio System', 'USB Port'],
            ['AC', 'Audio System', 'USB Port', 'Bluetooth'],
            ['AC', 'Audio System', 'USB Port', 'Bluetooth', 'Kamera Mundur'],
            ['AC', 'Audio System', 'USB Port', 'Bluetooth', 'Kamera Mundur', 'Parkir Otomatis'],
            ['AC', 'Leather Seats', 'GPS', 'Bluetooth', 'Sunroof'],
        ];

        for ($i = 0; $i < 25; $i++) {
            // Select random template or create new one
            if ($i < count($vehicleTemplates)) {
                $template = $vehicleTemplates[$i];
            } else {
                // Generate random vehicle data
                $models = ['Avanza', 'Brio', 'Sigra', 'Xenia', 'Agya', 'Innova', 'Fortuner', 'Civic', 'Ertiga', 'Mobilio', 'Jazz', 'HR-V', 'CR-V', 'Rush', 'Terios'];
                $selectedModel = $models[array_rand($models)];
                $selectedBrand = $brands->random();
                
                $template = [
                    'name' => $selectedBrand->name . ' ' . $selectedModel,
                    'model' => $selectedModel,
                    'category' => $categories->random()->name,
                    'brand' => $selectedBrand->name,
                    'year' => rand(2020, 2024),
                    'color' => $colors[array_rand($colors)],
                    'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                    'transmission' => $transmissions[array_rand($transmissions)],
                    'seats' => rand(5, 16),
                    'price_per_day' => rand(150000, 1200000),
                    'price_per_day_no_driver' => rand(100000, 1000000),
                    'machine_cc' => [1000, 1200, 1300, 1500, 1800, 2000, 2400, 2800, 3500][array_rand([1000, 1200, 1300, 1500, 1800, 2000, 2400, 2800, 3500])],
                    'description' => 'Kendaraan berkualitas dengan performa handal dan kenyamanan maksimal untuk berbagai kebutuhan perjalanan.',
                    'features' => $commonFeatures[array_rand($commonFeatures)],
                ];
            }

            // Find category and brand
            $category = $categories->firstWhere('name', $template['category']) ?? $categories->random();
            $brand = $brands->firstWhere('name', $template['brand']) ?? $brands->random();

            // Generate unique plate number
            $plateNumber = 'B ' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . ' ' . strtoupper(Str::random(3));

            // Create vehicle
            $vehicle = Vehicle::create([
                'name' => $template['name'],
                'slug' => Str::slug($template['name'] . '-' . uniqid()),
                'category_id' => $category->id,
                'member_id' => $members->isNotEmpty() ? $members->random()->id : null,
                'description' => $template['description'],
                'price_per_day' => $template['price_per_day'],
                'price_per_day_no_driver' => $template['price_per_day_no_driver'],
                'machine_cc' => $template['machine_cc'],
                'brand_id' => $brand->id,
                'brand' => $brand->name,
                'model' => $template['model'],
                'year' => $template['year'],
                'color' => $template['color'],
                'fuel_type' => $template['fuel_type'],
                'transmission' => $template['transmission'],
                'seats' => $template['seats'],
                'plate_number' => $plateNumber,
                'features' => $template['features'],
                'is_available' => true,
                'queue_number' => $i + 1,
            ]);

            // Select random image(s) from available images
            $numImages = rand(1, min(3, count($availableImages)));
            if ($numImages == 1) {
                $selectedImages = [array_rand($availableImages)];
            } else {
                $selectedImages = array_rand($availableImages, $numImages);
                if (!is_array($selectedImages)) {
                    $selectedImages = [$selectedImages];
                }
            }

            $order = 0;
            foreach ($selectedImages as $imageIndex) {
                $imageName = $availableImages[$imageIndex];
                $sourcePath = public_path('kendaraan/' . $imageName);
                
                if (!File::exists($sourcePath)) {
                    continue;
                }

                // Copy image to storage
                $extension = pathinfo($imageName, PATHINFO_EXTENSION);
                $newFileName = time() . '_' . uniqid() . '_' . Str::slug(pathinfo($imageName, PATHINFO_FILENAME)) . '.' . $extension;
                $storagePath = 'vehicles/' . $newFileName;
                
                // Copy file to storage
                $fileContent = File::get($sourcePath);
                Storage::disk('public')->put($storagePath, $fileContent);

                // Create thumbnail
                $thumbnailPath = ImageHelper::createThumbnail($storagePath);

                // Create vehicle image record
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $storagePath,
                    'thumbnail_path' => $thumbnailPath,
                    'is_featured' => $order === 0, // First image is featured
                    'order' => $order++,
                ]);
            }

            $this->command->info("Created vehicle: {$vehicle->name} (ID: {$vehicle->id})");
        }

        $this->command->info('Successfully created 25 vehicles with images!');
    }
}
