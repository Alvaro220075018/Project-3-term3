@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">My Job Applications</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($applications->count() > 0)
            <div class="space-y-4">
                @foreach($applications as $application)
                    <div class="p-4 border rounded shadow-sm">
                        <h2 class="text-xl font-semibold">
                            {{ $application->job->title }}
                        </h2>
                        <p class="text-gray-700">
                            Applied on {{ $application->created_at->format('d M Y') }}
                        </p>
                        <p class="mt-2">
                            <strong>Status:</strong>
                            <span class="capitalize">{{ $application->status }}</span>
                        </p>

                        @if($application->status === 'pending')
                            <div class="mt-4 flex space-x-2">
                                <form action="{{ route('applications.accept', $application) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Accept
                                    </button>
                                </form>

                                <form action="{{ route('applications.reject', $application) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-2 text-sm text-gray-600">
                                You have {{ $application->status }} this job.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p>You haven’t applied to any jobs yet.</p>
        @endif
    </div>
@endsection
