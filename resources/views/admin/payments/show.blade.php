<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-start mb-8 border-b pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Payment Detail: #{{ $payment->id }}
                </h1>
                <p class="text-gray-500 mt-1">Transaction ID: <span
                        class="font-medium text-gray-800">{{ $payment->transaction_id ?? 'N/A' }}</span></p>
            </div>
            <a href="{{ route('payments.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Logs
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Transaction Information
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                        <dd class="mt-1 text-lg font-bold text-green-600 sm:col-span-2 sm:mt-0">
                            ${{ number_format($payment->amount, 2) }}</dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @php
                                $currentStatus = $payment->booking->status ?? 'unknown';

                                $color =
                                    [
                                        'completed' => 'bg-green-100 text-green-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ][$currentStatus] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                {{ ucfirst($currentStatus) }}
                            </span>
                        </dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date/Time</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $payment->created_at->format('M d, Y H:i:s A') }}</dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $payment->payment_method ?? 'N/A' }}</dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Metadata / Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $payment->notes ?? 'No additional notes.' }}</dd>
                    </div>

                    {{-- User and Property Context --}}
                    <div class="px-4 py-5 sm:px-6 bg-indigo-50 border-t border-b border-indigo-200">
                        <h3 class="text-lg leading-6 font-medium text-indigo-800">
                            Associated Details
                        </h3>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Payer Details</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if ($payment->user)
                                {{ $payment->user->name }} ({{ $payment->user->email }})
                            @else
                                User Deleted
                            @endif
                        </dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Related Property</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{-- Changed to use nested relationship: booking->property --}}
                            @if ($payment->booking && $payment->booking->property)
                                <a href="{{ route('moderation.show', $payment->booking->property->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    {{ $payment->booking->property->title }} (ID:
                                    {{ $payment->booking->property->id }})
                                </a>
                            @else
                                Property Link N/A
                            @endif
                        </dd>
                    </div>

                    {{-- Assuming there's a link to a Booking/Invoice/Agreement here --}}
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Related Booking</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{-- Placeholder for a link to the booking/invoice detail --}}
                            <span class="text-gray-500">Link to Booking Detail...</span>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>

    </div>
</x-app-layout>
