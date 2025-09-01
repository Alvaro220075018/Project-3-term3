<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => view('dashboard.admin'),
            'provider' => view('dashboard.provider'),
            'seeker' => view('dashboard.seeker'),
            default => abort(403),
        };
    }
}
