<?php

use Illuminate\Http\Request;
use App\Models\Job;

Route::get('/jobs-nearby', function (Request $request) {
$lat = $request->query('lat');
$lng = $request->query('lng');

if (!$lat || !$lng) {
return response()->json([], 400);
}

$jobs = Job::nearby($lat, $lng, 10); // 10 km radius
return response()->json($jobs);
});
