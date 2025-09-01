<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Create a New Job
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 bg-white dark:bg-gray-800 rounded shadow">

        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf

            <input
                type="text"
                name="title"
                placeholder="Job Title"
                required
                class="mb-2 p-2 border rounded w-full"
                value="{{ old('title') }}"
            >
            @error('title')
            <p class="text-red-600">{{ $message }}</p>
            @enderror

            <textarea
                name="description"
                placeholder="Job Description"
                required
                class="mb-2 p-2 border rounded w-full"
            >{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-600">{{ $message }}</p>
            @enderror

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

            <!-- Map Preview -->
            <div id="map" style="height: 300px; width: 100%; margin-top: 1rem;"></div>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Post Job
            </button>
        </form>
    </div>

    <!-- Leaflet CSS & JS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map = L.map('map').setView([-25.7461, 28.1881], 13); // Default location (Pretoria)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                },
                (error) => {
                    console.error("Location error:", error);
                }
            );
        }
    </script>
</x-app-layout>
