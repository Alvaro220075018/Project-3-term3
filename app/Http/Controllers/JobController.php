<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;


class JobController extends Controller
{
    /**
     * Display a listing of the jobs (e.g., for admin or provider).
     */
    public function index()
    {
        $jobs = Job::latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show nearby jobs for seekers.
     */
    public function nearby()
    {
        return view('jobs.nearby');
    }

    /**
     * Show the form to create a new job (for providers).
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'nullable|string', // changed to nullable
        ]);

        $job = new Job();
        $job->user_id = auth()->id();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->latitude = $request->latitude;
        $job->longitude = $request->longitude;
        $job->location = $request->location ?? 'Unknown';
        $job->save();

        return redirect()->route('dashboard.provider')->with('success', 'Job posted successfully!');
    }

    public function providerJobs()
    {
        $jobs = auth()->user()->jobs()->latest()->get(); // assuming user has `jobs` relationship
        return view('provider.jobs.index', compact('jobs'));
    }
    public function fetchNearbyJobs(Request $request)
    {
        $latitude = $request->query('lat');
        $longitude = $request->query('lng');
        $radius = 10;

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Missing coordinates'], 400);
        }

        $jobs = Job::nearbyWithDistance($latitude, $longitude, $radius); // ✅ FIXED

        return response()->json(
            $jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => $job->description,
                    'location' => $job->location,
                    'distance' => round($job->distance, 2) . ' km',
                ];
            })
        );
    }






    /**
     * Display a specific job.
     */
    public function show(Job $job)
    {
        $application = null;

        if (Auth::check()) {
            $application = Application::where('job_id', $job->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('jobs.show', compact('job', 'application'));
    }



    /**
     * Show the form for editing a job.
     */
    public function edit(string $id)
    {
        $job = Job::findOrFail($id);
        return view('provider.jobs.edit', compact('job'));
    }

    /**
     * Update a job in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = Job::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('dashboard.provider')->with('success', 'Job updated successfully!');
    }

    /**
     * Delete a job.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return back()->with('success', 'Job deleted successfully.');
    }
}
