@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Provider Dashboard</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Create Job Button --}}
        <a href="{{ route('jobs.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Create New Job
        </a>

        {{-- Job Listings --}}
        <div>
            <h2 class="text-lg font-semibold mb-2">Your Posted Jobs</h2>

            @if($jobs->isEmpty())
                <p class="text-gray-600">You haven't posted any jobs yet.</p>
            @else
                @foreach($jobs as $job)
                    <div class="bg-white p-4 mb-3 border rounded shadow">
                        <h3 class="text-lg font-bold">{{ $job->title }}</h3>
                        <p class="text-gray-700">{{ $job->description }}</p>
                        <p class="text-sm text-gray-500 mb-2">{{ $job->location }}</p>

                        {{-- View Applicants Button --}}
                        <a href="{{ route('provider.applicants', $job->id) }}" class="text-sm inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            View Applicants
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
