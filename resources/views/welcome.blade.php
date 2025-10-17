@extends('layout.app')

@section('content')
    <section class="hero-slider">
        <!-- Slide 1 -->
        <div class="slide active"
            style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')">
            <div class="slide-content">
                <h1>Real Estate for Living and Investments</h1>
                <p>Discover modern rental homes and investment properties seamlessly. Search, chat, and book instantly.</p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide"
            style="background-image: url('https://images.unsplash.com/photo-1574362848149-11496d93a7c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')">
            <div class="slide-content">
                <h1>Find Your Perfect Home</h1>
                <p>Browse through thousands of properties to find the one that matches your lifestyle and budget.</p>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide"
            style="background-image: url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')">
            <div class="slide-content">
                <h1>Smart Real Estate Investments</h1>
                <p>Make informed decisions with our comprehensive property analytics and market insights.</p>
            </div>
        </div>

        <div class="slider-nav">
            <div class="slider-dot active" data-slide="0"></div>
            <div class="slider-dot" data-slide="1"></div>
            <div class="slider-dot" data-slide="2"></div>
        </div>
    </section>

    <div class="container">
        <!-- Added ID and changed action to # since JS will handle submission -->
        <form id="filter-form" action="#" method="GET" class="search-form">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label" for="search">Location</label>
                    <!-- Added ID for JS targeting -->
                    <input type="text" name="search" id="search"
                        placeholder="Enter City, neighborhood, or address..." value="{{ request('search') }}"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" for="max_rent">Max Rent</label>
                    <div class="range-container">
                        <!-- Added ID for JS targeting -->
                        <input type="range" name="max_rent" id="max_rent" min="0" max="10000" step="100"
                            value="{{ request('max_rent', 10000) }}" class="range-input">
                        <span class="range-display"
                            id="rent_display">${{ number_format(request('max_rent', 10000)) }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="start_date">Move-in Date</label>
                    <!-- Added ID for JS targeting -->
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="form-input">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-search form-input">Search</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <!-- Added ID for AJAX target -->
        <section class="properties-section" id="properties-section">
            <h2 class="section-title">Latest in Your Area</h2>

            <!-- This container will hold the dynamic content from AJAX -->
            <div id="properties-grid-container">
                <!-- Include the partial for the initial load -->
                @include('components.property_grid', ['properties' => $properties])
            </div>
        </section>
    </div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filter-form');
        const searchInput = document.getElementById('search');
        const maxRentInput = document.getElementById('max_rent');
        const rentDisplay = document.getElementById('rent_display');
        const startDateInput = document.getElementById('start_date');
        const gridContainer = document.getElementById('properties-grid-container');
        const propertiesSection = document.getElementById('properties-section');

        let fetchController = null; // Used to abort previous requests
        let isFetching = false;

        // --- 1. Real-time Rent Display Update ---
        function updateRentDisplay() {
            // Format number with commas
            const rentValue = parseInt(maxRentInput.value).toLocaleString('en-US');
            rentDisplay.textContent = `$${rentValue}`;
        }

        maxRentInput.addEventListener('input', updateRentDisplay);
        updateRentDisplay(); // Initialize display on page load

        // --- 2. Main AJAX Filtering Function ---
        async function fetchProperties(event, urlOverride = null) {
            if (event && event.preventDefault) {
                event.preventDefault(); // Stop form submission
            }

            // Abort previous request if one is in progress
            if (fetchController) {
                fetchController.abort();
            }

            isFetching = true;
            fetchController = new AbortController();
            const signal = fetchController.signal;

            // Add visual loading state
            gridContainer.style.opacity = '0.4';

            let url = urlOverride || `{{ route('welcome') }}`;
            const params = new URLSearchParams();

            // Only append filters if it's not a direct pagination link click (urlOverride handles pagination)
            if (!urlOverride || url.indexOf('page=') === -1) {
                params.append('search', searchInput.value);
                params.append('max_rent', maxRentInput.value);
                params.append('start_date', startDateInput.value);
            }

            // Append AJAX flag and current filters to the URL
            params.append('ajax', 1);

            const finalUrl = url + (url.includes('?') ? '&' : '?') + params.toString();

            try {
                const response = await fetch(finalUrl, { signal });

                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }

                const html = await response.text();

                // Replace the content of the grid container with the new results
                gridContainer.innerHTML = html;

            } catch (error) {
                if (error.name === 'AbortError') {
                    console.log('Fetch aborted.');
                } else {
                    console.error('Fetching properties failed:', error);
                    gridContainer.innerHTML = '<div class="no-properties"><p>Error loading properties. Please try again.</p></div>';
                }
            } finally {
                gridContainer.style.opacity = '1';
                isFetching = false;
                fetchController = null;
            }
        }

        // --- 3. Attach Listeners for Real-time Search ---

        // Use 'input' for search bar and 'change' for date/slider for better performance control
        searchInput.addEventListener('input', fetchProperties);
        startDateInput.addEventListener('change', fetchProperties);
        maxRentInput.addEventListener('change', fetchProperties);

        // Catch any 'Enter' key press in the form
        form.addEventListener('submit', fetchProperties);

        // Listen for clicks on the AJAX-loaded pagination links
        gridContainer.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                e.preventDefault();
                // Pass the pagination URL to the fetch function
                fetchProperties(null, e.target.href);
                // Scroll up for better UX after pagination
                propertiesSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endpush
