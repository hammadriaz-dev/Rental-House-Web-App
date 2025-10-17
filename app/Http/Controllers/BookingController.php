<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use Exception;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Catch_;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller {
    public function create( Property $property ) {
        return view( 'tenant.booking.create', compact( 'property' ) );
    }

    public function store( Property $property, Request $request ) {

        $userId = Auth::id();

        $rules = [
            'start_date'     => 'required|date|after_or_equal:today',
            'end_date'       => [
                'required',
                'date',
                'after:start_date',
                // Duration check ( at least one month )

                function ( $attribute, $value, $fail ) use ( $request ) {
                    $start = Carbon::parse( $request->start_date );
                    $end = Carbon::parse( $value );

                    if ( $end->lessThan( $start->copy()->addMonth() ) ) {
                        $fail( 'The booking period must be at least one month.' );
                    }
                },

                // Existing booking check
                function ( $attribute, $value, $fail ) use ( $request, $userId ) {
                    $start = Carbon::parse( $request->start_date );
                    $end = Carbon::parse( $value );

                    $existingBooking = Booking::where( 'user_id', $userId )
                    ->where( function ( $query ) use ( $start, $end ) {
                        $query->where( function ( $q ) use ( $start, $end ) {
                            $q->where( 'start_date', '<=', $end )
                            ->where( 'end_date', '>=', $start );
                        }
                    );
                }
            )
            ->exists();

            if ( $existingBooking ) {
                $fail( 'You already have an active booking during this period. You can book for a different time.' );
            }
        },
    ],
    'payment_method' => 'required|string|in:stripe,cod',
];

// Conditional Validation: ONLY add payment_token rule if method is stripe
if ( $request->input( 'payment_method' ) === 'stripe' ) {
    $rules[ 'payment_token' ] = 'required|string';
}

$validator = Validator::make( $request->all(), $rules );

if ( $validator->fails() ) {
    $errors = $validator->errors()->all();

    foreach ( $errors as $error ) {
        if ( str_contains( $error, 'at least one month' ) ) {
            Alert::error( 'Invalid Booking Duration', 'You must book for at least one month.' );
        } elseif ( str_contains( $error, 'active booking during this period' ) ) {
            Alert::error( 'Duplicate Booking', 'You already have an active booking for this period.' );
        } else {
            Alert::error( 'Validation Error', $error );
        }
    }

    return back()->withInput();
}

$validated = $validator->validated();

$totalAmount = $property->rent * 2;
$totalAmountCents = ( int )( $totalAmount * 100 );

$bookingStatus = 'pending';
$paymentRecord = [];

// 3. Process Payment
if ( $validated[ 'payment_method' ] === 'stripe' ) {
    try {
        // Initialize Stripe Client with secret key
        $stripe = new StripeClient( config( 'services.stripe.secret' ) );

        // Create a Charge using the token from the frontend
        $charge = $stripe->charges->create( [
            'amount'        => $totalAmountCents,
            'currency'      => 'usd',
            'source'        => $validated[ 'payment_token' ],
            'description'   => 'Booking for ' . $property->title,
            'receipt_email' => Auth::user()->email,
        ] );

        if ( $charge->status == 'succeeded' ) {
            $bookingStatus = 'confirmed';
            $paymentRecord = [
                'amount'         => $totalAmount,
                'payment_method' => 'Stripe',
                'transaction_id' => $charge->id,
                'payment_date'   => now(),
            ];
        } else {
            // Payment failed but Stripe didn't throw an exception (e.g., card declined)
                    return back()->withInput()->with( 'error', 'Stripe Payment Failed: ' . ($charge->failure_message ?? 'Card declined.') );
                }

            } catch ( Exception $e ) {
                // Catch API errors (e.g., invalid key, network error)
                return back()->withInput()->with( 'error', 'Payment processing error: ' . $e->getMessage() );
            }
        }

        // 4. Create Booking Record
        $booking = Auth::user()->bookings()->create( [
            'property_id'  => $property->id,
            'start_date'   => $validated[ 'start_date' ],
            'end_date'     => $validated[ 'end_date' ],
            'total_amount' => $totalAmount,
            'status'       => $bookingStatus,
        ] );

        // 5. Create Payment Record (only if payment was successful)
        if ( $bookingStatus === 'confirmed' ) {
            $booking->payment()->create( [
                'user_id'        => Auth::id(),
                'amount'         => $paymentRecord[ 'amount' ],
                'payment_method' => $paymentRecord[ 'payment_method' ],
                'payment_date'   => $paymentRecord[ 'payment_date' ],
                'transaction_id' => $paymentRecord[ 'transaction_id' ] ?? null,
            ] );
        }

        if($bookingStatus === 'confirmed' ){
            Alert::success('Booking confirmed!', 'Your payment was processed successfully.');
            return redirect()->back();
        }
        Alert::info('Booking Pending', 'Booking will confirm after visiting the site. We will contact you soon for confirmation.');
        return redirect()->back();
    }

    public function bookings(){
        $user = Auth::user();
        $bookings = $user->bookings()->with('property.images')->latest()->paginate(10);
        return view('tenant.booking.my_booking', compact('bookings'));

    }

    public function bookingRequest(){
        $landlordId = Auth::id();
        $propertiesWithBookings = Property::where('user_id', $landlordId)
                        ->whereHas('bookings', function ($query){
                            $query->where('status', 'Pending');
                        })
                        ->with(['bookings' => function($query){
                            $query->where('status', 'Pending')->with('user');
                        }])->get();
        return view('landlord.booking.requests', compact('propertiesWithBookings'));
    }
    public function bookingApprove(Booking $booking){
        if($booking->property->user_id !== Auth::id()){
            return back()->with('error', 'Unathorized action.');
        }
        $booking->status = 'confirmed';
        $booking->save();

        return back()->with('success', 'Booking request approved Successfully');
    }

    public function bookingReject(Booking $booking){
         if($booking->property->user_id !== Auth::id()){
            return back()->with('error', 'Unathorized action.');
        }
        $booking->status = 'Rejected';
        $booking->save();

        return back()->with('success', 'Booking request approved Successfully' );
        }
    }

