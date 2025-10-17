<div class="properties-grid">
    @forelse ($properties as $property)
        <div class="property-card">
            <div class="property-image">
                @php
                    // Helper to prioritize the primary image
                    // Assuming $property->primaryImage is a loaded relationship or accessor
                    $imagePath = optional($property->primaryImage)->image_path ?? optional($property->images->first())->image_path;
                @endphp
                @if ($imagePath)
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $property->title }}">
                @else
                    <div
                        style="display: flex; align-items: center; justify-content: center; height: 100%; background-color: #e0e0e0; color: #9e9e9e;">
                        [Property Image Not Available]
                    </div>
                @endif
                <div class="property-price">${{ number_format($property->rent, 0) }}/mo</div>
            </div>

            <div class="property-details">
                <h3 class="property-title">{{ $property->title }}</h3>
                <div class="property-location">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    </svg>
                    {{ $property->city ?? 'Unknown' }}, {{ $property->address ?? '' }}
                </div>

                <div class="property-actions">
                    <a href="{{ route('property_deatils', $property->id) }}" class="btn btn-view">
                        View Details
                    </a>

                    @auth
                        @role('tenant')
                            <div class="action-buttons">
                                <a href="{{ route('booking.create', $property->id) }}" class="btn btn-book">
                                    Book Now
                                </a>
                                <a href="{{ route('tenant.messages.app', $property->user_id) }}" class="btn btn-chat">
                                    Chat
                                </a>
                            </div>
                        @endrole
                    @else
                        <div class="action-buttons">
                            <a href="{{ route('login') }}" class="btn btn-book">
                                Book Now
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-chat">
                                Chat
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="no-properties">
            <p>No properties found matching your criteria.</p>
            <p>Please adjust your search criteria.</p>
        </div>
    @endforelse
</div>

<div class="pagination">
    {{-- Pagination links must retain all filter query parameters --}}
    {{ $properties->appends(request()->except('page'))->links() }}
</div>
