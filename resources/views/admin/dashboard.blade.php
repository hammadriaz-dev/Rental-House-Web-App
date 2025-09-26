<x-app-layout>

    <div class="max-w-full mx-auto">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Admin Dashboard</h1>

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-bold text-gray-700">TOTAL COLLECTIONS</h2>
                <div class="relative">
                    <select class="border-gray-300 rounded-lg text-sm">
                        <option>All Properties</option>
                        <option>Property 1</option>
                        <option>Property 2</option>
                    </select>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-green-600 mb-6">$5,500 <span class="text-xl text-gray-400">USD</span></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-green-600 p-4 rounded-xl text-white shadow-md flex flex-col justify-between h-32">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.885a2 2 0 014.242 0M10 10l.477.477a4 4 0 015.656 0l4 4a4 4 0 10-5.656 5.656L14 14m-1.414-9.9l1.414 1.414"></path></svg>
                        <span class="font-medium">ONLINE COLLECTION</span>
                    </div>
                    <p class="text-2xl font-extrabold">$8,500 <span class="text-sm font-normal">USD</span></p>
                </div>

                <div class="bg-blue-500 p-4 rounded-xl text-white shadow-md flex flex-col justify-between h-32">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14zM4 5h16a2 2 0 012 2v1H2V7a2 2 0 012-2z"></path></svg>
                        <span class="font-medium">PENDING PROPERTIES</span>
                    </div>
                    <p class="text-2xl font-extrabold">11 <span class="text-sm font-normal">LISTINGS</span></p>
                </div>

                <div class="bg-yellow-500 p-4 rounded-xl text-white shadow-md flex flex-col justify-between h-32">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2m0 2a2 2 0 100-4m-7 0a2 2 0 11-4 0m4 0v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2m8 0h2m-6-8a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z"></path></svg>
                        <span class="font-medium">TOTAL USERS</span>
                    </div>
                    <p class="text-2xl font-extrabold">2,500 <span class="text-sm font-normal">ACCOUNTS</span></p>
                </div>

                <div class="bg-red-500 p-4 rounded-xl text-white shadow-md flex flex-col justify-between h-32">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span class="font-medium">GROSS REVENUE</span>
                    </div>
                    <p class="text-2xl font-extrabold">$55,000 <span class="text-sm font-normal">USD</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Property Booking Trend</h3>
                    <select class="border-gray-300 rounded-lg text-xs py-1">
                        <option>Monthly</option>
                        <option>Quarterly</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-1/4 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/6 bg-red-400 opacity-75"></div>
                        </div>
                        <span class="mt-1 block">Feb</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-3/5 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/3 bg-red-400 opacity-75"></div>
                        </div>
                        <span class="mt-1 block">Mar</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-2/3 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/5 bg-red-400 opacity-75"></div>
                        </div>
                        <span class="mt-1 block">Apr</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-1/2 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/2 bg-red-400 opacity-75"></div>
                        </div>
                        <span class="mt-1 block">May</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-4/5 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/4 bg-red-400 opacity-75"></div>
                        </div>
                        <span class="mt-1 block">Jun</span>
                    </div>
                </div>
                <div class="flex justify-center mt-4 text-xs space-x-4">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span> Confirmed</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-400 mr-1"></span> Canceled</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-4">Vacancy Rate</h3>
                    <div class="flex justify-center items-center h-48">
                        <div class="w-32 h-32 rounded-full border-8 border-green-500 border-r-gray-300 border-b-gray-300 transform rotate-45 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-3xl font-bold">4</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-sm font-medium text-gray-600">Total Properties: 4</p>
                    </div>
                </div>
                <div class="flex justify-center text-sm mt-4 space-x-6">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span> Total Units</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-300 mr-1"></span> Vacant Units</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Revenue to Expenses</h3>
                    <select class="border-gray-300 rounded-lg text-xs py-1">
                        <option>Monthly</option>
                        <option>Quarterly</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-1/2 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/4 bg-red-500"></div>
                        </div>
                        <span class="mt-1 block">Feb</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-2/3 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/3 bg-red-500"></div>
                        </div>
                        <span class="mt-1 block">Mar</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-4/5 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/5 bg-red-500"></div>
                        </div>
                        <span class="mt-1 block">Apr</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-1/3 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-2/3 bg-red-500"></div>
                        </div>
                        <span class="mt-1 block">May</span>
                    </div>
                    <div class="flex-1 text-center text-xs text-gray-500">
                        <div class="h-48 bg-gray-200 rounded-t-lg relative">
                            <div class="absolute bottom-0 w-full h-3/4 bg-green-500"></div>
                            <div class="absolute bottom-0 w-full h-1/2 bg-red-500"></div>
                        </div>
                        <span class="mt-1 block">Jun</span>
                    </div>
                </div>
                <div class="flex justify-center mt-4 text-xs space-x-4">
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span> Revenue</span>
                    <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-1"></span> Expenses</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
