<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentLogController extends Controller
{
    public function index(Request $request){
        // Basic Query and eager loading
        $query = Payment::query()->with(['user', 'booking.property'])->latest();

        // Basic Search Filter
        if($search = $request->input('search')){
            $query->where(function ($q) use ($search){
                $q->where('transaction_id', 'like', '%' .$search. '%')
                  ->orWhere('amount', (float)$search);
            });
        }

        // Filter BY Status
        if($status = $request->input('status')){
            if(in_array($status, ['confirmed', 'pending', 'failed'])){
                $query->where('status', $status);
            }
        }

        // Fetch Paginate Results
        $payments = $query->paginate(20)->withQueryString();

        // Get Unique status for filter dropdown
         if ($statuses = $request->input('status')) {
            // Check if the status is one of the valid booking statuses
            if (in_array($statuses, ['pending', 'confirmed', 'completed', 'cancelled'])) { // Use your Booking statuses here

                // FIX: Use whereHas to filter the payments based on the booking's status
                $query->whereHas('booking', function ($q) use ($statuses) {
                    $q->where('status', $statuses);
                });
            }
        }

        return view('admin.payments.index', compact('payments', 'statuses'));
    }

    public function show(Payment $payment)
    {
        // Eager load relationships for the detailed view
        $payment->load('user', 'booking.property');

        return view('admin.payments.show', compact('payment'));
    }
}
