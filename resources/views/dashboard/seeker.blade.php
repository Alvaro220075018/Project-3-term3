@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-30xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h1>

        {{-- Nearby Jobs Section --}}
        <div class="bg-white rounded shadow p-4 mb-6">
            <h2 class="text-xl font-semibold mb-2">Nearby Jobs</h2>
            <p id="location-status" class="text-sm text-gray-600 mb-4">Detecting your location...</p>

            <div id="jobs-list" class="space-y-4"></div>
        </div>
    </div>

    {{-- JS Script --}}
    <script>
        function fetchNearbyJobs(lat, lng) {
            fetch(`/jobs/nearby-fetch?lat=${lat}&lng=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('jobs-list');
                    const status = document.getElementById('location-status');

                    list.innerHTML = ""; // Clear previous results

                    if (!data.length) {
                        status.textContent = "No nearby jobs found.";
                        return;
                    }

                    status.textContent = `Found ${data.length} jobs near you`;

                    data.forEach(job => {
                        const div = document.createElement('div');
                        div.classList.add('p-4', 'border', 'rounded', 'bg-gray-50');
                        div.innerHTML = `
                            <h3 class="text-lg font-bold">${job.title}</h3>
                            <p class="text-sm text-gray-700 mb-1">${job.description}</p>
                            <p class="text-xs text-gray-500">${job.location}</p>
                            <a href="/jobs/${job.id}" class="text-blue-600 hover:underline text-sm">View Details</a>
                        `;
                        list.appendChild(div);
                    });
                })
                .catch(() => {
                    const list = document.getElementById('jobs-list');
                    list.innerHTML = "";
                    document.getElementById('location-status').textContent = "Error fetching nearby jobs.";
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => fetchNearbyJobs(pos.coords.latitude, pos.coords.longitude),
                err => {
                    document.getElementById('location-status').textContent = "Location access denied or unavailable.";
                }
            );
        } else {
            document.getElementById('location-status').textContent = "Geolocation not supported.";
        }
    </script>
@endsection
