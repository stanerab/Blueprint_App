<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;

class DashboardController
{
    public function index()
    {
        Auth::requireLogin();
        
        $sessions = Session::getAll();
        $patients = Patient::getAll();
        
        view('dashboard.dashboard', [
            'sessions' => $sessions,
            'patients' => $patients,
            'user' => Auth::user()
        ]);
    }
}