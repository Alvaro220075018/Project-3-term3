<?php
namespace App\Http\Controllers;

class SeekerDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.seeker');
    }
}
