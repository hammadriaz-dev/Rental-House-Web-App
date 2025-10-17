@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <span class="text-indigo-600">Admin Panel:</span> Payment Logs
            </h1>
            <p class="text-gray-500">Total Transactions: {{ $payments->total() }}</p>
        </div>

        {{-- Filters and Search Form --}}
        <form method="GET" action="{{ route('payments.index') }}"
            class="bg-white shadow rounded-lg p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            {{-- Search by ID/Amount --}}
            <div class="col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Txn ID / Amount</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Enter ID or Amount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Filter by Status --}}
            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    @foreach (['completed', 'pending', 'failed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by User --}}
            <div class="col-span-1">
                <label for="user_search" class="block text-sm font-medium text-gray-700">User Name/Email</label>
                <input type="text" name="user_search" id="user_search" value="{{ request('user_search') }}"
                    placeholder="User Name or Email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Submit and Reset Buttons --}}
            <div class="col-span-1 flex space-x-2">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Filter
                </button>
                <a href="{{ route('payments.index') }}"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Reset
                </a>
            </div>
        </form>

        {{-- Payment Logs Table --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaction ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payer (User)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Property</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ Str::limit($payment->transaction_id ?? 'N/A', 15) }}
                                        @if ($payment->payment_method)
                                            <div class="text-xs text-gray-400">({{ $payment->payment_method }})</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        ${{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $currentStatus = $payment->booking->status ?? 'unknown';

                                            $color =
                                                [
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'confirmed' => 'bg-green-100 text-green-800', // Added confirmed
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                    'cancelled' => 'bg-red-100 text-red-800', // Added cancelled
                                                ][$currentStatus] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                            {{ ucfirst($currentStatus) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900"
                                            title="{{ $payment->user->email ?? 'N/A' }}">
                                            {{ $payment->user->name ?? 'Deleted User' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($payment->booking && $payment->booking->property)
                                            <a href="{{ route('moderation.show', $payment->booking->property->id) }}"
                                                class="text-sm text-gray-700 hover:text-indigo-600">
                                                {{ Str::limit($payment->booking->property->title, 25) }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Carbon::parse($payment->created_at)->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">View Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-lg text-gray-500">
                                        No payment logs found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
