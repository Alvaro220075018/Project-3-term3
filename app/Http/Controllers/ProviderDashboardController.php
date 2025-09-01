<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job; // Import Job model

class ProviderDashboardController extends Controller
{
    public function index()
    {
        // Get all jobs posted by the currently authenticated provider (user)
        $jobs = Job::where('user_id', auth()->id())
            ->latest() // optional: order newest first
            ->get();

        // Pass jobs to the dashboard view
        return view('dashboard.provider', compact('jobs'));
    }
}
