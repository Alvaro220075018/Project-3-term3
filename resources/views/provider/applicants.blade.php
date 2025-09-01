@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Applicants for "{{ $job->title }}"</h1>

        @forelse($applications as $application)
            {{ dd($application->id) }}

            <div class="bg-white shadow rounded p-4 mb-4">
                <p><strong>Name:</strong> {{ $application->user->name }}</p>
                <p><strong>Email:</strong> {{ $application->user->email }}</p>
                <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>

                @if($application->status === 'pending again')
                    <form action="{{ route('applications.provider.accept', ['application' => $application->id]) }}" method="POST"
                    class="inline-block mr-2">

                    @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Accept</button>
                    </form>

                    <form action="{{ route('applications.provider.reject', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                    </form>
                @endif

            </div>
        @empty
            <p>No applicants yet.</p>
        @endforelse
    </div>
@endsection
