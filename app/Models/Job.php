<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    // Allow mass assignment
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'latitude',
        'longitude',
    ];

    /**
     * Scope to get nearby jobs based on location and radius (in KM).
     */
    public static function nearby($latitude, $longitude, $radius = 10)
    {
        return static::selectRaw("*,
            (6371 * acos(cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude)))) AS distance", [
            $latitude, $longitude, $latitude
        ])
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->get();
    }
    public static function nearbyWithDistance($latitude, $longitude, $radius = 10)
    {
        return static::selectRaw("jobs.*,
        (6371 * acos(
            cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(latitude))
        )) AS distance", [
            $latitude, $longitude, $latitude
        ])
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->get();
    }

    // In App\Models\Job.php
    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        $haversine = "(6371 * acos(cos(radians($latitude))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians($longitude))
                    + sin(radians($latitude))
                    * sin(radians(latitude))))";

        return $query->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }



    /**
     * A job belongs to a user (provider)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

}
