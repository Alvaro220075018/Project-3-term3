<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Store a new job application.
     */
    public function store(Request $request, $jobId)
    {
        $user = Auth::user();

        // Find the job using the ID from the route
        $job = Job::findOrFail($jobId);

        // Check if application already exists
        $existing = Application::where('job_id', $job->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied to this job.');
        }

        $application = new Application();
        $application->job_id = $job->id;
        $application->user_id = $user->id;
        $application->status = 'pending';
        $application->save();

        return back()->with('success', 'Job application submitted.');
    }

    /**
     * Accept a job offer (by seeker).
     */
    public function accept(Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $application->update(['status' => 'accepted']);
        return back()->with('success', 'You accepted the job.');


    }

    /**
     * Reject a job offer (by seeker).
     */
    public function reject(Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $application->update(['status' => 'rejected']);
        return back()->with('success', 'You rejected the job.');
    }

    public function viewApplicants(Job $job)
    {
        $user = Auth::user();

        // Ensure the job belongs to the current provider
        if ($job->user_id !== $user->id) {
            abort(403);
        }

        $applications = Application::with('user')
            ->where('job_id', $job->id)
            ->get();

        return view('provider.applicants', compact('job', 'applications'));
        dd($applications->pluck('id'));

    }


    public function providerAccept(Application $application)
    {
        $provider = Auth::user();

        // Ensure the job belongs to the current provider
        if ($application->job->user_id !== $provider->id) {
            abort(403);
        }

        $application->update(['status' => 'accepted']);
        return back()->with('success', 'Application accepted.');
    }

    public function providerReject(Application $application)
    {
        $provider = Auth::user();

        if ($application->job->user_id !== $provider->id) {
            abort(403);
        }

        $application->update(['status' => 'rejected']);
        return back()->with('success', 'Application rejected.');
    }


}
