<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller {
    // ADMIN

    public function admin() {

        // --- 1. Key Performance Indicators (KPIs) ---

        // Total Users
        $totalUsers = User::count();
        // Total Pending Properties
        $pendingProperties = Property::where('moderation_status', 'pending')->count();
        // Booking
        $completedBookings = Booking::whereIn('status', ['confirmed', 'completed'])->get();
        $grossRevenue = $completedBookings->sum('total_amount');

        // total amount for all payments record
        $onlineCollection = Payment::sum('amount');

        // --- 2. Chart Data: Property Booking Trend (Last 6 Months) ---
        $months = [];
        $confirmedData = [];
        $canceledData = [];
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        for($i = 0; $i < 6; $i++){
            $month = $startDate->copy()->addMonths($i);
            $months[] = $month->format('M');

            // Confirmed Booking Count
            $confirmedCount = Booking::where('status', 'confirmed')
                              ->whereYear('created_at', $month->year)
                              ->whereMonth('created_at', $month->month)
                              ->count();
            $confirmedData[] = $confirmedCount;

            // Canceled Bookings Count
            $canceledCount = Booking::where('status', 'confirmed')
                              ->whereYear('created_at', $month->year)
                              ->whereMonth('created_at', $month->month)
                              ->count();
            $canceledData[] = $canceledCount;
        }

        // --- 3. Vacancy Rate Calculation ---
        $totalProperties = Property::count();
        // Propertied that are currently available
        $availableProperties = Property::where('status', 'available')
                                        ->where('moderation_status', 'approved')
                                        ->count();

        $vacancyRate = $totalProperties > 0 ? round(($availableProperties / $totalProperties) * 100, 1) : 0;

        // Data structure for the gauge chart
        $vacancyData = [
            'total' => $totalProperties,
            'available' => $availableProperties,
            'booked' => $totalProperties - $availableProperties,
            'rate' => $vacancyRate,
        ];

        // ----------------------------------------------------------------------
        // --- 4. Monthly Revenue Trend Data (REVENUE ONLY) ---
        // ----------------------------------------------------------------------

        $monthlyRevMonths = [];
        $monthlyRevenue = [];
        $revExpStartDate = Carbon::now()->subMonths(5)->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $month = $revExpStartDate->copy()->addMonths($i);
            $monthlyRevMonths[] = $month->format('M');

            // DYNAMIC REVENUE CALCULATION: Sum 'amount' from 'payments' for the month
            $revenueAmount = Payment::whereYear('created_at', $month->year)
                                    ->whereMonth('created_at', $month->month)
                                    ->sum('amount');

            $monthlyRevenue[] = (int) $revenueAmount;
        }

        $monthlyRevenueData = [
            'months' => $monthlyRevMonths,
            'revenue' => $monthlyRevenue,
        ];


        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'pendingProperties' => $pendingProperties,
            'grossRevenue' => $grossRevenue,
            'onlineCollection' => $onlineCollection,
            'bookingTrend' => [
                'months' => $months,
                'confirmed' => $confirmedData,
                'canceled' => $canceledData,
            ],
            'vacancyData' => $vacancyData,
            'monthlyRevenueData' => $monthlyRevenueData,
        ]);
    }

    // TENANT

    public function tenant() {
        $user = Auth::user();
        $today = Carbon::today();

        // --- 1. Fetch Current/Active Rental ---
        $activeBooking = Booking::with( 'property' )
        ->where( 'user_id', $user->id )
        ->where( 'status', 'Active' )
        // FIX 1: Use 'start_date' instead of 'move_in_date'
        ->latest( 'start_date' )
        ->first();

        $currentRentalData = null;
        $nextPaymentData = [
            'amount' => null,
            'due_date' => null,
            'days_remaining' => null,
            'status_color' => 'gray',
            'status_text' => 'No active rental.'
        ];

        if ( $activeBooking ) {
            $property = $activeBooking->property;

            // Card 1: Current Rental Data
            $currentRentalData = [
                'title' => $property->title,
                'status' => 'Active',
                // FIX 2: Use 'end_date' instead of 'move_out_date'
                'lease_end_date' => $activeBooking->end_date ?
                Carbon::parse( $activeBooking->end_date )->toFormattedDateString() :
                'Ongoing',
            ];

            // Card 2: Next Payment Calculation ( Simplistic monthly cycle )
            $rentAmount = $property->rent ?? 0;
            // Use property's 'rent' column
        // FIX 3: Use 'start_date' instead of 'move_in_date'
        $startDate = Carbon::parse($activeBooking->start_date);
        $nextPaymentDate = null;

        // Calculate the next payment date based on the start_date's day of the month
            $potentialDueDate = Carbon::today()->day( $startDate->day );

            // If the payment day has passed this month, set it for next month
            if ( $potentialDueDate->isPast() && !$potentialDueDate->isToday() ) {
                $nextPaymentDate = $potentialDueDate->addMonth();
            } else {
                $nextPaymentDate = $potentialDueDate;
            }

            // Ensure the calculated payment date is within the lease term
            // FIX 4: Use 'end_date' instead of 'move_out_date'
            if ( $activeBooking->end_date && $nextPaymentDate->greaterThan( Carbon::parse( $activeBooking->end_date ) ) ) {
                $nextPaymentData[ 'status_text' ] = 'Final month paid or lease ending.';
            } else {
                $daysRemaining = $today->diffInDays( $nextPaymentDate, false );

                $nextPaymentData[ 'amount' ] = number_format( $rentAmount, 2 );
                $nextPaymentData[ 'due_date' ] = $nextPaymentDate->toFormattedDateString();

                if ( $daysRemaining < 0 ) {
                    $nextPaymentData[ 'status_color' ] = 'red';
                    $nextPaymentData[ 'status_text' ] = 'Payment Overdue!';
                } elseif ( $daysRemaining <= 7 ) {
                    $nextPaymentData[ 'status_color' ] = 'red';
                    $nextPaymentData[ 'status_text' ] = "Due in $daysRemaining days (" . $nextPaymentData[ 'due_date' ] . ')';
                } else {
                    $nextPaymentData[ 'status_color' ] = 'blue';
                    $nextPaymentData[ 'status_text' ] = "Due in $daysRemaining days (" . $nextPaymentData[ 'due_date' ] . ')';
                }
            }
        }

        // --- 2. Count Unread Messages ( Mocked/Simplified ) ---
        // Implement real Message counting logic here if available.
        $unreadMessageCount = 0;

        // --- 3. Fetch Recent Bookings ---
        $recentBookings = Booking::with( 'property' )
        ->where( 'user_id', $user->id )
        // FIX 5: Use 'start_date' instead of 'move_in_date'
        ->orderBy( 'start_date', 'desc' )
        ->limit( 5 )
        ->get();

        return view( 'tenant.dashboard', [
            'currentRentalData' => $currentRentalData,
            'nextPaymentData' => $nextPaymentData,
            'unreadMessageCount' => $unreadMessageCount,
            'recentBookings' => $recentBookings,
            'user' => $user,
        ] );
    }

    // LANDLORD

    public function landlord() {
        $user = Auth::user();

        // 1. Fetch Key Performance Indicators (KPIs)

        // Total Properties owned by the landlord
        $totalProperties = Property::where('user_id', $user->id)->count();

        // Total Pending Booking Requests for the landlord's properties
        $landlordPropertyIds = Property::where('user_id', $user->id)->pluck('id');

        $pendingRequestsCount = Booking::whereIn('property_id', $landlordPropertyIds)
                                       ->where('status', 'pending')
                                       ->count();

        // Total Occupied Properties (Properties with an active booking)
        $occupiedPropertiesCount = Property::where('user_id', $user->id)
            ->whereHas('bookings', function ($query) {
                $query->whereIn('status', ['confirmed', 'completed'])
                      ->where('end_date', '>=', Carbon::now()->toDateString());
            })
            ->distinct()
            ->count();

        // 2. Fetch Recent Pending Booking Requests (for the table)
        // Filter by the properties the landlord owns and status is 'pending'
        $pendingRequests = Booking::with('user:id,name', 'property:id,title')
            ->whereIn('property_id', $landlordPropertyIds)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('landlord.dashboard', [
            'totalProperties' => $totalProperties,
            'pendingRequestsCount' => $pendingRequestsCount,
            'occupiedPropertiesCount' => $occupiedPropertiesCount,
            'pendingRequests' => $pendingRequests,
        ]);
    }

}
