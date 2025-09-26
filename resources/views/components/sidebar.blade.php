<aside
    :class="open ? 'translate-x-0' : '-translate-x-64'"
    class="fixed z-40 inset-y-0 left-0 w-64 bg-white shadow-md transform transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:inset-0"
>
    <div class="p-4 text-2xl font-black text-green-600 border-b flex justify-start items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span class="tracking-tighter">rento</span>

        <button @click="open = false" class="md:hidden text-gray-600 hover:text-black ml-auto">
            ✖
        </button>
    </div>

    <nav class="p-4 space-y-2 text-sm">

        @role('admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded text-gray-700 font-semibold hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2m0 2a2 2 0 100-4m-7 0a2 2 0 11-4 0m4 0v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2m8 0h2m-6-8a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zM4 16h3m-3 0h3"></path></svg>
                User Management
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-2a7 7 0 00-7-7h14a7 7 0 00-7 7v2"></path></svg>
                Property Moderation
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 14h.01M12 11h.01M15 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Payment Logs
            </a>
        @endrole

        @role('landlord')
            <a href="{{ route('landlord.dashboard') }}" class="flex items-center px-4 py-2 rounded text-gray-700 font-semibold hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('property.create') }}" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-2a7 7 0 00-7-7h14a7 7 0 00-7 7v2"></path></svg>
                My Properties
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M17 11h.01M9 15h.01M15 15h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Booking Requests
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Add Property
            </a>
        @endrole

        @role('tenant')
            <a href="{{ route('tenant.dashboard') }}" class="flex items-center px-4 py-2 rounded text-gray-700 font-semibold hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M17 11h.01M9 15h.01M15 15h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                My Bookings
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                Messages
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded text-gray-700 hover:bg-green-50 hover:text-green-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.05 13.5l3.85-3.85M13.5 11.05l-3.85 3.85M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                My Reviews
            </a>
        @endrole
    </nav>
</aside>
