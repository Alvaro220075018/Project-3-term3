<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\SeekerDashboardController;

// 🏠 Public Homepage
Route::get('/', function () {
    return view('welcome');
});

// 🔐 Auth Routes from Breeze
require __DIR__.'/auth.php';

// ✅ Universal Dashboard Redirect Based on Role
Route::middleware('auth')->get('/dashboard', function () {
    $role = Auth::user()->role;

    return match ($role) {
        'admin' => redirect()->route('dashboard.admin'),
        'provider' => redirect()->route('dashboard.provider'),
        'seeker' => redirect()->route('dashboard.seeker'),
        default => redirect('/'),
    };
})->name('dashboard');

// 🧑‍💼 Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

    Route::get('/admin/users', [AdminUserController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.delete');

    Route::get('/admin/disputes', [AdminUserController::class, 'disputes'])->name('admin.disputes');
});

// 🧑‍🔧 Provider Routes
Route::middleware(['auth', 'role:provider'])->group(function () {
    Route::get('/dashboard/provider', [ProviderDashboardController::class, 'index'])->name('dashboard.provider');

    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');

    Route::get('/jobs/{job}/applicants', [ApplicationController::class, 'viewApplicants'])->name('jobs.applicants');

    Route::post('/rate/{seekerId}', [RatingController::class, 'rate'])->name('rate.seeker');
    Route::get('/provider/jobs', [JobController::class, 'providerJobs'])->name('provider.jobs.index');
    Route::post('/applications/{application}/provider-accept', [ApplicationController::class, 'providerAccept'])->name('applications.provider.accept');
    Route::post('/applications/{application}/provider-reject', [ApplicationController::class, 'providerReject'])->name('applications.provider.reject');
    Route::get('/provider/jobs/{job}/applicants', [ApplicationController::class, 'viewApplicants'])->name('provider.applicants');

});

// 🧑‍💼 Seeker Routes
Route::middleware(['auth', 'role:seeker'])->group(function () {
    Route::get('/dashboard/seeker', [SeekerDashboardController::class, 'index'])->name('dashboard.seeker');

    Route::get('/jobs/nearby', [JobController::class, 'nearby'])->name('jobs.nearby'); // HTML page
    Route::get('/jobs/nearby-fetch', [JobController::class, 'fetchNearbyJobs'])->name('jobs.nearby.fetch'); // JSON data

    // Apply to job (with {job} parameter)
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('applications.store');

    Route::get('/applications/history', [HistoryController::class, 'index'])->name('applications.history');
    Route::get('/ratings', [RatingController::class, 'show'])->name('ratings.seeker');

    // Accept/Reject application
    Route::post('/applications/{application}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
});

// Public Job details page (anyone can view)
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Authenticated profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
