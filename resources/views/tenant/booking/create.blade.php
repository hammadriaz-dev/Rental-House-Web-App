@extends('layout.app')

@section('content')
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="lg:grid lg:grid-cols-3">

                    <div
                        class="lg:col-span-2 p-8 md:p-12 bg-gray-50 dark:bg-gray-900 border-r border-gray-100 dark:border-gray-700">

                        <div class="mb-8">
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Finalize Booking</h3>
                            <p class="text-gray-600 dark:text-gray-400">Please select your payment method for the property:
                                <b>**{{ $property->title ?? 'Property' }}**.</b></p>
                        </div>

                        <form id="booking-form" action="{{ route('booking.store', $property->id) }}" method="POST"
                            class="space-y-8">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">
                            {{-- Hidden field to store the Stripe Token/ID --}}
                            <input type="hidden" name="payment_token" id="payment_token">

                            <div class="space-y-6">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white border-b pb-2">1. Rental Period
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="start_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Check-in
                                            Date</label>
                                        <input type="date" id="start_date" name="start_date" required
                                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-black focus:ring-black transition">
                                    </div>
                                    <div>
                                        <label for="end_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Check-out
                                            Date</label>
                                        <input type="date" id="end_date" name="end_date" required
                                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-black focus:ring-black transition">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6 pt-4">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white border-b pb-2">2. Payment Method
                                </h4>

                                <div>
                                    <label for="payment_method"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select
                                        Payment Method</label>
                                    <select id="payment_method" name="payment_method" required
                                        class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:border-black focus:ring-black transition">
                                        <option value="">Select a method...</option>
                                        <option value="stripe">Stripe (Pay Now)</option>
                                        <option value="cod">COD (Cash on Delivery)</option>
                                    </select>
                                </div>

                                <div id="card_details_section"
                                    class="space-y-4 border border-dashed border-gray-300 dark:border-gray-600 p-4 rounded-lg hidden">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Enter Credit Card Details
                                        securely via Stripe.</p>

                                    {{-- This is the container where Stripe.js will inject the card element --}}
                                    <div id="card-element"
                                        class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                    </div>

                                    {{-- Used to display form errors (e.g., invalid card number) --}}
                                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                                </div>
                            </div>

                            <button type="submit" id="confirm_booking_btn"
                                class="w-full px-6 py-3 rounded-xl font-bold text-white text-lg bg-black dark:bg-white dark:text-black shadow-lg hover:opacity-80 transition disabled:bg-gray-400">
                                Complete Booking & Pay Now ${{ number_format($property->rent * 2 ?? 0, 2) }}
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-1 p-8 md:p-12 bg-white dark:bg-gray-800">
                        <h4 class="text-xl font-extrabold text-gray-900 dark:text-white mb-4">Summary</h4>

                        {{-- Property Image --}}
                        <div class="mb-4">
                            @if (isset($property->primaryImage) && $property->primaryImage->image_path)
                                <img src="{{ asset('storage/' . $property->primaryImage->image_path) }}"
                                    alt="{{ $property->title }}" class="w-full h-48 object-cover rounded-lg shadow">
                            @else
                                <div class="flex items-center justify-center h-48 bg-gray-200 text-gray-500 rounded-lg">
                                    [Property Image Not Available]
                                </div>
                            @endif
                        </div>

                        {{-- Property Details --}}
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Property: {{ $property->title ?? 'N/A' }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">
                            Amount Due: <span class="font-bold">${{ number_format($property->rent * 2 ?? 0, 2) }}</span>
                        </p>

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            (This includes first month's rent and security deposit.)
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Include Stripe.js library --}}
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ensure config('services.stripe.key') is correctly configured in your .env
                const stripe = Stripe("{{ config('services.stripe.key') }}");
                const elements = stripe.elements();
                const cardElement = elements.create('card', {
                    style: {
                        base: {
                            // Use appropriate dark mode/light mode styles
                            color: '{{ Auth::user()->theme === 'dark' ? '#ffffff' : '#1f2937' }}',
                            '::placeholder': {
                                color: '{{ Auth::user()->theme === 'dark' ? '#9ca3af' : '#9ca3af' }}'
                            },
                            fontSize: '16px',
                        }
                    }
                });

                const paymentMethod = document.getElementById('payment_method');
                const cardDetailsSection = document.getElementById('card_details_section');
                const cardErrors = document.getElementById('card-errors');
                const bookingForm = document.getElementById('booking-form');
                const confirmBtn = document.getElementById('confirm_booking_btn');
                const paymentTokenInput = document.getElementById('payment_token');

                // --- Card Element Visibility ---
                function updatePaymentFields() {
                    const selectedMethod = paymentMethod.value;
                    const isStripe = selectedMethod === 'stripe';
                    const totalAmount = '{{ number_format($property->rent * 2 ?? 0, 2) }}';

                    if (isStripe) {
                        cardDetailsSection.classList.remove('hidden');
                        cardElement.mount('#card-element');
                        confirmBtn.textContent = `Complete Booking & Pay Now $${totalAmount}`;
                    } else {
                        cardDetailsSection.classList.add('hidden');
                        try {
                            cardElement.unmount();
                        } catch (e) {}
                        cardErrors.textContent = ''; // Clear errors

                        if (selectedMethod === 'cod') {
                            confirmBtn.textContent = `Confirm Booking (Payment Due on Delivery)`;
                        } else {
                            confirmBtn.textContent = `Complete Booking & Pay Now $${totalAmount}`;
                        }
                    }
                }

                // --- Form Submission Handler ---
                bookingForm.addEventListener('submit', async function(event) {
                    if (paymentMethod.value === 'stripe') {
                        event.preventDefault(); // Stop default form submission for Stripe
                        confirmBtn.disabled = true;

                        // Clear previous errors
                        cardErrors.textContent = '';

                        // 1. Validate dates before tokenization (client-side check)
                        if (!document.getElementById('start_date').value || !document.getElementById(
                                'end_date').value) {
                            alert('Please select a check-in and check-out date.');
                            confirmBtn.disabled = false;
                            return;
                        }

                        // 2. Create Stripe Token
                        const {
                            token,
                            error
                        } = await stripe.createToken(cardElement);

                        if (error) {
                            // Inform the user if there was an error
                            cardErrors.textContent = error.message;
                            confirmBtn.disabled = false;
                        } else {
                            // Send the token to your server
                            paymentTokenInput.value = token.id;
                            bookingForm.submit(); // Submit the form with the token
                        }
                    }
                    // Allow direct submission for COD
                });

                // --- Event Listeners ---
                cardElement.addEventListener('change', function(event) {
                    cardErrors.textContent = event.error ? event.error.message : '';
                });

                updatePaymentFields();
                paymentMethod.addEventListener('change', updatePaymentFields);
            });
        </script>
    @endpush
@endsection
