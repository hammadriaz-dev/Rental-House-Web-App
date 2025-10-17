<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        // Start with a base query
        $query = Property::query();

        // **CRITICAL FIX: Filter by Moderation Status**
        $query->where('moderation_status', 'approved');

        // Eager load images to avoid N+1 queries
        $query->with(['images' => function ($q) {
            $q->orderBy('is_primary', 'desc');
        }]);

        // Only show properties that are listed and available
        $query->where('status', 'available');

        // --- 1. Filter by Search (Location/Title/Description) ---
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // --- 2. Filter by Max Rent ---
        if ($maxRent = $request->input('max_rent')) {
            $query->where('rent', '<=', (float)$maxRent);
        }

        // --- 3. Filter by Move-in Date (Simplified Listing Date Filter) ---
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '<=', $request->input('start_date'));
        }

        // Order properties by latest creation date
        $query->latest();

        // Fetch properties with pagination
        $properties = $query->paginate(9)->withQueryString();

        // --- Handle AJAX Request for Real-time Filtering ---
        if ($request->has('ajax') || $request->ajax()) {
            // Ensure this view path is correct: resources/views/components/property_grid.blade.php
            return view('components.property_grid', compact('properties'))->render();
        }

        // --- Handle Initial Page Load ---
        return view('welcome', compact('properties'));
    }

    public function showPropertyDetails(Property $property){

        // **CRITICAL FIX: Abort if not approved**
        if ($property->moderation_status !== 'approved') {
            // Abort with 404 Not Found to prevent leaking unapproved content
            abort(404);
        }

        $property->load(['images' => function ($q) {
                $q->orderBy('is_primary', 'desc');
        }]);

        return view('property_details', compact('property'));
    }
}
