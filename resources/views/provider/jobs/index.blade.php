<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">My Posted Jobs</h2>
    </x-slot>

    <div class="p-6 space-y-4">
        @if ($jobs->isEmpty())
            <p>No jobs posted yet.</p>
        @else
            <table class="min-w-full border">
                <thead>
                <tr>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Posted On</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($jobs as $job)
                    <tr>
                        <td class="border px-4 py-2">{{ $job->title }}</td>
                        <td class="border px-4 py-2">{{ Str::limit($job->description, 50) }}</td>
                        <td class="border px-4 py-2">{{ $job->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
