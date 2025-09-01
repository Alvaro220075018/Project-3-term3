@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Nearby Jobs</h1>

        <div id="location-status" class="mb-4 text-gray-700">
            <p>Detecting your location...</p>
        </div>

        <div id="jobs-list" class="space-y-4"></div>
    </div>

    <script>
        function fetchNearbyJobs(latitude, longitude) {
            fetch(`/jobs/nearby-fetch?lat=${latitude}&lng=${longitude}`)
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('jobs-list');
                    const status = document.getElementById('location-status');
                    status.innerText = `Found ${data.length} job(s) near you`;

                    if (data.length === 0) {
                        list.innerHTML = `<p>No nearby jobs found.</p>`;
                        return;
                    }

                    data.forEach(job => {
                        const item = document.createElement('div');
                        item.classList.add('p-4', 'border', 'rounded', 'bg-white', 'shadow');

                        item.innerHTML = `
                            <h2 class="text-xl font-semibold mb-1">${job.title}</h2>
                            <p class="mb-2">${job.description}</p>
                            <p class="text-sm text-gray-500 mb-2">Distance: ${parseFloat(job.distance).toFixed(2)} km</p>
                            <a href="/jobs/${job.id}" class="text-blue-600 hover:underline mb-2 inline-block">View Job</a>

                            <form method="POST" action="/jobs/${job.id}/accept" class="mt-2">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    Accept Job
                                </button>
                            </form>
                        `;

                        list.appendChild(item);
                    });
                })
                .catch(error => {
                    document.getElementById('location-status').innerText = "Failed to fetch jobs.";
                    console.error(error);
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    fetchNearbyJobs(position.coords.latitude, position.coords.longitude);
                },
                error => {
                    document.getElementById('location-status').innerText = "Unable to retrieve your location.";
                }
            );
        } else {
            document.getElementById('location-status').innerText = "Geolocation is not supported by your browser.";
        }
    </script>
@endsection
