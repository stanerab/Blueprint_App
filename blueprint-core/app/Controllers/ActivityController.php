<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\ActivityLog;

class ActivityController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index()
    {
        // Get all activities (you might want to paginate this)
        try {
            $activities = ActivityLog::getRecent(50); // Get last 50 activities
        } catch (\Exception $e) {
            $activities = [];
            error_log("Error fetching activities: " . $e->getMessage());
        }

        view('activities', [
            'activities' => $activities,
            'title' => 'All Activities'
        ]);
    }

    public function byWard($ward)
    {
        $ward = ucfirst(strtolower($ward));
        
        try {
            $activities = ActivityLog::getByWard($ward, 50);
        } catch (\Exception $e) {
            $activities = [];
            error_log("Error fetching activities: " . $e->getMessage());
        }

        view('activities', [
            'activities' => $activities,
            'ward' => $ward,
            'title' => $ward . ' Ward Activities'
        ]);
    }
}