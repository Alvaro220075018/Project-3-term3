@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">{{ $job->title }}</h1>

        <p class="text-gray-700 mb-2"><strong>Category:</strong> {{ $job->category ?? 'N/A' }}</p>
        <p class="text-gray-700 mb-2"><strong>Description:</strong> {{ $job->description }}</p>
        <p class="text-gray-700 mb-2"><strong>Location:</strong> {{ $job->location }}</p>
        <p class="text-gray-700 mb-2"><strong>Posted:</strong> {{ $job->created_at->diffForHumans() }}</p>

        {{-- Map is visible for everyone --}}
        <div id="map" class="w-full h-64 mb-6 rounded shadow"></div>

        @auth
            {{-- Seeker: Apply button --}}
            @if(auth()->id() !== $job->user_id && empty($application))
                <form action="{{ route('applications.store', $job->id) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Apply for Job
                    </button>
                </form>
            @endif

            {{-- Seeker: Application status --}}
            @if(!empty($application) && auth()->id() === $application->user_id)
                <p class="mt-4 font-semibold">
                    Status:
                    <span class="capitalize {{
                    $application->status === 'accepted' ? 'text-green-600' :
                    ($application->status === 'rejected' ? 'text-red-600' : 'text-yellow-600')
                }}">
                    {{ $application->status }}
                </span>
                </p>
            @endif

            {{-- Provider: Applicants list --}}
            @if(!empty($applications) && auth()->id() === $job->user_id)
                <h2 class="text-xl font-bold mt-6 mb-2">Applicants</h2>
                @forelse($applications as $app)
                    <div class="bg-white shadow rounded p-4 mb-3">
                        <p><strong>Name:</strong> {{ $app->user->name }}</p>
                        <p><strong>Email:</strong> {{ $app->user->email }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($app->status) }}</p>

                        @if($app->status === 'pending')
                            <div class="mt-2 flex gap-2">
                                <form action="{{ route('applications.provider.accept', ['application' => $app->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Accept</button>
                                </form>
                                <form action="{{ route('applications.provider.reject', ['application' => $app->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Reject</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p>No applicants yet.</p>
                @endforelse
            @endif
        @endauth
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Map always shows for everyone
        const jobLat = parseFloat("{{ $job->latitude ?? 0 }}");
        const jobLng = parseFloat("{{ $job->longitude ?? 0 }}");

        const map = L.map('map').setView([jobLat, jobLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        if (jobLat && jobLng) {
            L.marker([jobLat, jobLng])
                .addTo(map)
                .bindPopup("Job Location: {{ $job->location }}")
                .openPopup();
        }
    </script>
@endsection
