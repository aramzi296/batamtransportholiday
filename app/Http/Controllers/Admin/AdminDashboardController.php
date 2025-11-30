<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('is_available', true)->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Recent bookings
        $recentBookings = Booking::with(['vehicle', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly booking statistics (current year)
        $monthlyBookings = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyBookings[] = Booking::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'totalArticles',
            'publishedArticles',
            'totalCustomers',
            'recentBookings',
            'monthlyBookings'
        ));
    }
}
