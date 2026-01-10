<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVehicleController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminArticleCategoryController;
use App\Http\Controllers\Admin\AdminAvailabilityController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminEmailTestController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\FonnteWebhookController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', function () {
    return view('vehicles.index-livewire');
})->name('vehicles.index');
Route::get('/vehicles/{slug}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::post('/vehicles/{slug}/contact', [VehicleController::class, 'contact'])->name('vehicles.contact');
Route::post('/vehicles/{id}/check-availability', [VehicleController::class, 'checkAvailability'])->name('vehicles.check-availability');
Route::get('/prices', [\App\Http\Controllers\PriceController::class, 'index'])->name('prices.index');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Member Authentication Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [\App\Http\Controllers\Member\MemberAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [\App\Http\Controllers\Member\MemberAuthController::class, 'register']);
        Route::get('/login', [\App\Http\Controllers\Member\MemberAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Member\MemberAuthController::class, 'login']);
    });
    
    Route::post('/logout', [\App\Http\Controllers\Member\MemberAuthController::class, 'logout'])->name('logout');
    
    // Member Dashboard (Protected)
    Route::middleware(['auth', 'member'])->group(function () {
        Route::get('/dasbor', [\App\Http\Controllers\Member\MemberDashboardController::class, 'index'])->name('dashboard');
        
        // Profil Anggota
        Route::get('/profile', [\App\Http\Controllers\Member\MemberDashboardController::class, 'profileIndex'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Member\MemberDashboardController::class, 'profileUpdate'])->name('profile.update');
        
        // Daftar Kendaraan
        Route::get('/vehicles', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesIndex'])->name('vehicles.index');
        Route::get('/vehicles/create', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesCreate'])->name('vehicles.create');
        Route::post('/vehicles', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesStore'])->name('vehicles.store');
        Route::get('/vehicles/{vehicle}/edit', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesEdit'])->name('vehicles.edit');
        Route::put('/vehicles/{vehicle}', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesUpdate'])->name('vehicles.update');
        Route::delete('/vehicles/{vehicle}', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesDestroy'])->name('vehicles.destroy');
        Route::get('/vehicles/{slug}', [\App\Http\Controllers\Member\MemberDashboardController::class, 'vehiclesShow'])->name('vehicles.show');
        
        // Hari Off
        Route::get('/off-days', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysIndex'])->name('off-days.index');
        Route::get('/off-days/create', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysCreate'])->name('off-days.create');
        Route::post('/off-days', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysStore'])->name('off-days.store');
        Route::get('/off-days/{offDay}/edit', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysEdit'])->name('off-days.edit');
        Route::put('/off-days/{offDay}', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysUpdate'])->name('off-days.update');
        Route::delete('/off-days/{offDay}', [\App\Http\Controllers\Member\MemberDashboardController::class, 'offDaysDestroy'])->name('off-days.destroy');
        
        // Daftar Member
        Route::get('/members', [\App\Http\Controllers\Member\MemberDashboardController::class, 'membersIndex'])->name('members.index');
        
        // Manajemen Akun
        Route::get('/account', [\App\Http\Controllers\Member\MemberDashboardController::class, 'accountIndex'])->name('account.index');
        Route::put('/account', [\App\Http\Controllers\Member\MemberDashboardController::class, 'accountUpdate'])->name('account.update');
        Route::post('/account/change-password', [\App\Http\Controllers\Member\MemberDashboardController::class, 'accountChangePassword'])->name('account.change-password');
    });
});

// Booking Routes (Public & Authenticated)
Route::get('/booking', function () {
    return view('bookings.create-livewire');
})->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/thank-you/{id}', [BookingController::class, 'thankYou'])->name('booking.thank-you');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/password/change', [\App\Http\Controllers\ChangePasswordController::class, 'showChangeForm'])->name('password.change.form');
        Route::post('/password/change', [\App\Http\Controllers\ChangePasswordController::class, 'change'])->name('password.change');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Vehicle Management
    Route::resource('vehicles', AdminVehicleController::class);
    Route::post('vehicles/{vehicle}/set-queue-number', [AdminVehicleController::class, 'setQueueNumber'])->name('vehicles.set-queue-number');
    Route::resource('vehicle-categories', AdminCategoryController::class, [
        'names' => [
            'index' => 'vehicle-categories.index',
            'create' => 'vehicle-categories.create',
            'store' => 'vehicle-categories.store',
            'show' => 'vehicle-categories.show',
            'edit' => 'vehicle-categories.edit',
            'update' => 'vehicle-categories.update',
            'destroy' => 'vehicle-categories.destroy',
        ]
    ]);
    Route::resource('brands', AdminBrandController::class);
    Route::patch('brands/{brand}/toggle-active', [AdminBrandController::class, 'toggleActive'])->name('brands.toggle-active');
    
    // Rental Categories Management
    Route::resource('rental-categories', \App\Http\Controllers\Admin\AdminRentalCategoryController::class);
    
    // Booking Management
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update']);
    Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/send-confirmation-email', [AdminBookingController::class, 'sendConfirmationEmail'])->name('bookings.send-confirmation-email');
    Route::post('bookings/{booking}/send-confirmation-whatsapp', [AdminBookingController::class, 'sendConfirmationWhatsApp'])->name('bookings.send-confirmation-whatsapp');
    
    // Article Management
    Route::resource('articles', AdminArticleController::class)->parameters([
        'articles' => 'article:slug'
    ]);
    Route::resource('article-categories', AdminArticleCategoryController::class, [
        'names' => [
            'index' => 'article-categories.index',
            'create' => 'article-categories.create',
            'store' => 'article-categories.store',
            'show' => 'article-categories.show',
            'edit' => 'article-categories.edit',
            'update' => 'article-categories.update',
            'destroy' => 'article-categories.destroy',
        ]
    ]);
    
    // Availability Management
    Route::get('availability', [AdminAvailabilityController::class, 'index'])->name('availability.index');
    Route::post('availability', [AdminAvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('availability/{id}', [AdminAvailabilityController::class, 'destroy'])->name('availability.destroy');
    
    // Testimonial Management
    Route::resource('testimonials', AdminTestimonialController::class);
    Route::patch('testimonials/{testimonial}/toggle-active', [AdminTestimonialController::class, 'toggleActive'])->name('testimonials.toggle-active');
    Route::patch('testimonials/{testimonial}/toggle-featured', [AdminTestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
    
    // FAQ Management
    Route::resource('faqs', AdminFaqController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
    Route::get('users/{user}/profile', [\App\Http\Controllers\Admin\AdminUserController::class, 'profile'])->name('users.profile');
    Route::put('users/{user}/profile', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateProfile'])->name('users.profile.update');
    
    // Vehicle Calendar (Block Dates)
    Route::get('vehicles/{vehicle}/calendar', [\App\Http\Controllers\Admin\AdminVehicleCalendarController::class, 'index'])->name('vehicles.calendar');
    Route::get('vehicles/{vehicle}/calendar/block', [\App\Http\Controllers\Admin\AdminVehicleCalendarController::class, 'create'])->name('vehicles.calendar.block');
    Route::post('vehicles/{vehicle}/calendar/block', [\App\Http\Controllers\Admin\AdminVehicleCalendarController::class, 'store'])->name('vehicles.calendar.block.store');
    Route::delete('vehicles/{vehicle}/calendar/{calendar}', [\App\Http\Controllers\Admin\AdminVehicleCalendarController::class, 'destroy'])->name('vehicles.calendar.block.delete');
    
    // Email Test
    Route::get('email-test', [AdminEmailTestController::class, 'index'])->name('email-test.index');
    Route::post('email-test/send', [AdminEmailTestController::class, 'sendTest'])->name('email-test.send');
    
    // Media Management
    Route::resource('media', AdminMediaController::class)->only(['index', 'store', 'update', 'destroy']);
});

// Debug: Check rental categories satuan
Route::get('/debug/rental-categories', function () {
    try {
        // Check columns using PRAGMA for SQLite
        $columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(rental_categories)");
        $columnNames = array_map(fn($col) => $col->name, $columns);
        $hasSatuan = in_array('satuan', $columnNames);
        
        // If column doesn't exist, add it
        if (!$hasSatuan) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE rental_categories ADD COLUMN satuan VARCHAR(255) NULL');
            $hasSatuan = true;
        }
        
        // Update data
        $updates = [
            'Per Jam' => 'jam',
            'Setengah Hari' => 'setengah hari',
            'Per Hari' => 'hari',
            'Per Bulan' => 'bulan',
        ];
        
        foreach ($updates as $name => $satuan) {
            \Illuminate\Support\Facades\DB::table('rental_categories')
                ->where('name', $name)
                ->update(['satuan' => $satuan]);
        }
        
        // Get all categories
        $categories = \App\Models\RentalCategory::orderBy('id')->get();
        
        return response()->json([
            'status' => 'success',
            'column_exists' => $hasSatuan,
            'columns' => $columnNames,
            'data' => $categories->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'satuan' => $cat->satuan ?? 'NULL',
                    'has_satuan' => isset($cat->satuan) && !empty($cat->satuan),
                ];
            }),
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.rental-categories');

// Contact & Static Pages
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', function () {
    return view('pages.about');
})->name('about');
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');
Route::get('/register-terms', function () {
    return view('pages.register_terms');
})->name('register.terms');

// Password Reset
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// WhatsApp API Routes
Route::post('/api/whatsapp/send-booking', [WhatsAppController::class, 'sendBookingMessage'])->name('api.whatsapp.send-booking');
Route::post('/api/whatsapp/test', [WhatsAppController::class, 'test'])->name('api.whatsapp.test');
Route::get('/test-whatsapp', function () {
    return view('test-whatsapp');
})->name('test.whatsapp');

// Fonnte Webhook Routes
Route::post('/api/fonnte/webhook', [FonnteWebhookController::class, 'handle'])->name('api.fonnte.webhook');
Route::get('/api/fonnte/test', [FonnteWebhookController::class, 'test'])->name('api.fonnte.test');



// Test WhatsApp (legacy route - can be removed if not needed)
Route::get('testwa',function(){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://app.saungwa.com/api/create-message',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
    'appkey' => '7d389aad-ba64-4330-bde9-79aac1c52b48',
    'authkey' => 'lX0GKhWw3rCJBcErpWRpQZTfz5IszhomAMm5o8dxRZ6qMfcMh6',
    'to' => '628117007201',
    'message' => 'Halo, saya ingin bertanya tentang kendaraan. test wa berjalan dengan sukses',
    'sandbox' => 'false'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
})->name('testwa');