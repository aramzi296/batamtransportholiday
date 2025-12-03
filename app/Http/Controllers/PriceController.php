<?php

namespace App\Http\Controllers;

use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        // Get all active categories with prices
        $categories = VehicleCategory::active()
            ->where(function($query) {
                $query->whereNotNull('price')
                      ->orWhereNotNull('price_with_driver');
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('prices.index', compact('categories'));
    }
}
