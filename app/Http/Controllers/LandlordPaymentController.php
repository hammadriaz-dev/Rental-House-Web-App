<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class LandlordPaymentController extends Controller
{
    public function index(){
        $user = Auth::user();
        $landlordPropertyId = Property::where('user_id', $user->id)->pluck('id');

        if ($landlordPropertyId->isEmpty()) {
            $payments = new LengthAwarePaginator([], 0, 15);
            return view('landlord.payments.index', compact('payments'));
        }

        $payments = Payment::with(['booking.property', 'booking.user'])
            ->whereHas('booking', function ($query) use ($landlordPropertyId) {
                $query->whereIn('property_id', $landlordPropertyId);
            })
            ->latest()
            ->paginate(15);

        return view('landlord.payments.index', compact('payments'));
    }
}
