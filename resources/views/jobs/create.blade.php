@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-8 p-6 bg-white shadow rounded">

        <h2 class="text-2xl font-bold mb-4">Post a Job</h2>

        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf

            <!-- Job Title -->
            <label for="title" class="block font-semibold mb-1">Job Title</label>
            <input type="text" name="title" id="title" placeholder="e.g. Babysitter in Sunnyside"
                   required class="mb-4 p-2 border rounded w-full">

            <!-- Job Description -->
            <label for="description" class="block font-semibold mb-1">Description</label>
            <textarea name="description" id="description" placeholder="Describe the job..."
                      required class="mb-4 p-2 border rounded w-full"></textarea>

            <!-- Hidden Location Fields -->
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <!-- Map Preview -->
            <label class="block font-semibold mb-1">Your Location</label>
            <div id="map" style="height: 300px; width: 100%; margin-bottom: 1rem;"></div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Post Job
            </button>
        </form>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Geolocation Script -->
    <script>
        let map = L.map('map').setView([-25.7461, 28.1881], 13); // Default to Pretoria

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
@endsection
