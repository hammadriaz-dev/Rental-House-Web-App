@php
    // Calculate current month's collection (used for the large top number)
    $currentMonthCollection = \App\Models\Payment::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('amount');
@endphp
<x-app-layout>

    <div class="max-w-full mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-extrabold text-indigo-700">CURRENT MONTH'S COLLECTION</h2>
                <div class="relative">
                    {{-- Property Filter: This part is complex to implement dynamically without JS/Livewire. We'll leave it static for demonstration. --}}
                    <select class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option>All Properties</option>
                        {{-- @foreach (\App\Models\Property::select('title')->limit(5)->get() as $property)
                            <option>{{ $property->title }}</option>
                        @endforeach --}}
                        <option>Property 1 (Mock)</option>
                        <option>Property 2 (Mock)</option>
                    </select>
                </div>
            </div>

            {{-- Dynamic Total Collection --}}
            <p class="text-5xl font-extrabold text-green-600 mb-6">
                ${{ number_format($currentMonthCollection, 0) }}
                <span class="text-xl text-gray-400">USD</span>
            </p>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- ONLINE COLLECTION --}}
                <div class="bg-green-600 p-4 rounded-xl text-white shadow-xl flex flex-col justify-between h-32 transform hover:scale-[1.02] transition duration-300">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.885a2 2 0 014.242 0M10 10l.477.477a4 4 0 015.656 0l4 4a4 4 0 10-5.656 5.656L14 14m-1.414-9.9l1.414 1.414"></path></svg>
                        <span class="font-medium">TOTAL ONLINE COLLECTION</span>
                    </div>
                    <p class="text-2xl font-extrabold">${{ number_format($onlineCollection, 0) }} <span class="text-sm font-normal">USD</span></p>
                </div>

                {{-- PENDING PROPERTIES --}}
                <div class="bg-blue-500 p-4 rounded-xl text-white shadow-xl flex flex-col justify-between h-32 transform hover:scale-[1.02] transition duration-300">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14zM4 5h16a2 2 0 012 2v1H2V7a2 2 0 012-2z"></path></svg>
                        <span class="font-medium">PENDING PROPERTIES</span>
                    </div>
                    <p class="text-2xl font-extrabold">{{ number_format($pendingProperties) }} <span class="text-sm font-normal">LISTINGS</span></p>
                </div>

                {{-- TOTAL USERS --}}
                <div class="bg-yellow-500 p-4 rounded-xl text-white shadow-xl flex flex-col justify-between h-32 transform hover:scale-[1.02] transition duration-300">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2m0 2a2 2 0 100-4m-7 0a2 2 0 11-4 0m4 0v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2m8 0h2m-6-8a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"></path></svg>
                        <span class="font-medium">TOTAL USERS</span>
                    </div>
                    <p class="text-2xl font-extrabold">{{ number_format($totalUsers) }} <span class="text-sm font-normal">ACCOUNTS</span></p>
                </div>

                {{-- GROSS REVENUE --}}
                <div class="bg-red-500 p-4 rounded-xl text-white shadow-xl flex flex-col justify-between h-32 transform hover:scale-[1.02] transition duration-300">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span class="font-medium">GROSS REVENUE (Confirmed)</span>
                    </div>
                    <p class="text-2xl font-extrabold">${{ number_format($grossRevenue, 0) }} <span class="text-sm font-normal">USD</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- 1. Property Booking Trend (Bar Chart) --}}
            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Property Booking Trend</h3>
                    <select class="border-gray-300 rounded-lg text-xs py-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Last 6 Months</option>
                        <option>Last 12 Months</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    @foreach ($bookingTrend['months'] as $index => $month)
                        @php
                            $confirmed = $bookingTrend['confirmed'][$index];
                            $canceled = $bookingTrend['canceled'][$index];
                            $total = $confirmed + $canceled;

                            // Max value for scaling
                            $maxVal = max(max($bookingTrend['confirmed']), max($bookingTrend['canceled']), 10);

                            $confirmedHeight = $total > 0 ? round(($confirmed / $maxVal) * 100) : 0;
                            $canceledHeight = $total > 0 ? round(($canceled / $maxVal) * 100) : 0;

                            // Scale the total height up to the max height (h-48)
                            $totalHeightScale = $total > 0 ? round(($total / $maxVal) * 100) : 0;
                            $barHeightClass = 'h-[' . $totalHeightScale . '%]'; // Tailwind JIT hack or inline style
                        @endphp
                        <div class="flex-1 text-center text-xs text-gray-500 h-full flex flex-col justify-end">
                            <div class="h-full bg-gray-200 rounded-t-lg relative" style="height: 100%;">
                                {{-- Total bar height scaled --}}
                                <div class="absolute bottom-0 w-full rounded-t-lg"
                                     style="height: {{ $totalHeightScale }}%; background-color: #e5e7eb;">

                                    {{-- Confirmed bookings segment (Green) --}}
                                    <div class="absolute bottom-0 w-full bg-green-500 rounded-t-lg"
                                         style="height: {{ $confirmed > 0 ? max(5, round(($confirmed / $total) * 100)) : 0 }}%;"
                                         title="Confirmed: {{ $confirmed }}">
                                    </div>

                                    {{-- Canceled bookings segment (Red) - Stacked above Confirmed --}}
                                    <div class="absolute w-full bg-red-400 rounded-t-lg"
                                         style="height: {{ $canceled > 0 ? max(5, round(($canceled / $total) * 100)) : 0 }}%; bottom: {{ $confirmed > 0 ? max(5, round(($confirmed / $total) * 100)) : 0 }}%;"
                                         title="Canceled: {{ $canceled }}">
                                    </div>
                                </div>
                            </div>
                            <span class="mt-2 block font-medium">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center mt-6 text-sm space-x-6">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Confirmed Bookings</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-400 mr-2"></span> Canceled Bookings</span>
                </div>
            </div>

            {{-- 2. Vacancy Rate (Gauge Chart) --}}
            <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-4">Vacancy Rate (Available Properties)</h3>
                    <div class="flex justify-center items-center h-48">
                        @php
                            // Vacant = available / total
                            // Occupied = (total - available) / total
                            // We are focusing on Vacancy (Available)
                            $vacancyAngle = $vacancyData['rate'] * 1.8; // 180 degrees max
                            $occupiedAngle = 180 - $vacancyAngle;

                            // Dynamic CSS for the gauge animation/display
                            $gaugeStyle = "
                                background: conic-gradient(
                                    #10B981 0deg {$vacancyAngle}deg, /* Green for Available */
                                    #e5e7eb {$vacancyAngle}deg 180deg, /* Gray for Booked */
                                    transparent 180deg 360deg
                                );
                            ";
                        @endphp
                        <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center relative">
                            {{-- Outer arc (180 degrees) --}}
                            <div class="w-40 h-20 absolute top-0 overflow-hidden rounded-t-full" style="{{ $gaugeStyle }}">
                                <div class="w-40 h-20 bg-white rounded-full absolute bottom-0"></div>
                            </div>
                            {{-- Inner Circle to show value --}}
                            <div class="w-32 h-32 rounded-full bg-white shadow-inner flex flex-col items-center justify-center">
                                <p class="text-4xl font-extrabold text-indigo-600">{{ $vacancyData['available'] }}</p>
                                <p class="text-sm font-medium text-gray-500">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-sm font-medium text-gray-600">Total Properties: **{{ $vacancyData['total'] }}**</p>
                        <p class="text-2xl font-extrabold mt-2 text-green-600">{{ $vacancyData['rate'] }}% Vacancy</p>
                    </div>
                </div>
                <div class="flex justify-center text-sm mt-4 space-x-6">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Available</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span> Occupied/Other</span>
                </div>
            </div>

            {{-- 3. Monthly Revenue Trend (Single Bar Chart) --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    {{-- Updated Title --}}
                    <h3 class="font-semibold text-gray-700">Monthly Revenue Trend</h3>
                    <select class="border-gray-300 rounded-lg text-xs py-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Last 6 Months</option>
                        <option>Quarterly</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    {{-- Loop through the new monthlyRevenueData --}}
                    @foreach ($monthlyRevenueData['months'] as $index => $month)
                        @php
                            $rev = $monthlyRevenueData['revenue'][$index];
                            // Find the max value for scaling the bars
                            $maxVal = max(max($monthlyRevenueData['revenue']), 1);

                            // Calculate the height percentage relative to the tallest bar
                            $revHeight = $rev > 0 ? round(($rev / $maxVal) * 100) : 0;
                        @endphp
                        <div class="flex-1 text-center text-xs text-gray-500">
                            <div class="h-48 bg-gray-100 rounded-t-lg relative flex flex-col justify-end">
                                {{-- Revenue Bar (Full Width) --}}
                                <div class="absolute bottom-0 w-full bg-indigo-500 rounded-t-lg"
                                     style="height: {{ $revHeight }}%;"
                                     title="Revenue: ${{ number_format($rev) }}">
                                </div>
                            </div>
                            <span class="mt-1 block font-medium">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center mt-6 text-sm space-x-4">
                    {{-- Updated Legend --}}
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></span> Total Revenue</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
