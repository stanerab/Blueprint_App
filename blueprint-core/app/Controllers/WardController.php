<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;

class WardController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN WARD VIEW (Dynamic)
    |--------------------------------------------------------------------------
    */

    public function show($ward)
    {
        $ward = ucfirst(strtolower($ward));

        $allowed = ['Hope', 'Manor', 'Lakeside'];
        if (!in_array($ward, $allowed)) {
            die('Invalid ward.');
        }

        $this->loadWardData($ward);
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD FULL WARD DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function loadWardData($ward)
    {
        $userId = $_SESSION['user_id'];

        $totalBeds = [
            'Hope' => 12,
            'Manor' => 10,
            'Lakeside' => 10
        ][$ward];

        $patients = Patient::getActiveByWardAndUser($ward, $userId);
        usort($patients, fn($a, $b) => (int)$a->room_number - (int)$b->room_number);

        $sessions = Session::getByWardAndUser($ward, $userId);

        $archivedPatients = Patient::getArchivedByWardAndUser($ward, $userId);
        usort($archivedPatients, fn($a, $b) => (int)$a->room_number - (int)$b->room_number);

        $archivedSessions = Session::getArchivedByWardAndUser($ward, $userId);

        $discharged = Patient::getDischargedByWardAndUser($ward, $userId);
        usort($discharged, fn($a, $b) => (int)$a->room_number - (int)$b->room_number);

        $core10AdmissionCompleted = count(array_filter($patients, fn($p) => $p->core10_admission));
        $core10DischargeCompleted = count(array_filter($discharged, fn($p) => $p->core10_discharge));

        $occupiedBeds = count($patients);

        $stats = [
            'total_beds' => $totalBeds,
            'occupied_beds' => $occupiedBeds,
            'available_beds' => $totalBeds - $occupiedBeds,
            'core10_admission_completed' => $core10AdmissionCompleted,
            'core10_discharge_completed' => $core10DischargeCompleted,
            'total_sessions' => count($sessions),
            'sessions_today' => count(array_filter($sessions, fn($s) =>
                strpos($s->datetime, date('Y-m-d')) === 0
            )),
            'discharged_this_month' => count(array_filter($discharged, fn($p) =>
                strpos($p->discharge_date ?? '', date('Y-m')) === 0
            ))
        ];

        view('wards.' . strtolower($ward), [
            'ward' => $ward,
            'patients' => $patients,
            'sessions' => $sessions,
            'archivedPatients' => $archivedPatients,
            'archivedSessions' => $archivedSessions,
            'discharged' => array_slice($discharged, 0, 5),
            'stats' => $stats,
            'totalDischarged' => count($discharged),
            'core10AdmissionCompleted' => $core10AdmissionCompleted,
            'core10DischargeCompleted' => $core10DischargeCompleted
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ARCHIVED PATIENTS
    |--------------------------------------------------------------------------
    */

    public function archivedPatients($ward)
    {
        $ward = ucfirst(strtolower($ward));
        $archivedPatients = Patient::getArchivedByWard($ward);

        usort($archivedPatients, fn($a, $b) =>
            (int)$a->room_number - (int)$b->room_number
        );

        view('wards.archived-patients', [
            'ward' => $ward,
            'archivedPatients' => $archivedPatients,
            'title' => $ward . ' Ward - Archived Patients'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ARCHIVED SESSIONS
    |--------------------------------------------------------------------------
    */

    public function archivedSessions($ward)
    {
        $ward = ucfirst(strtolower($ward));
        $archivedSessions = Session::getArchivedByWard($ward);

        view('wards.archived-sessions', [
            'ward' => $ward,
            'archivedSessions' => $archivedSessions,
            'title' => $ward . ' Ward - Archived Sessions'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DISCHARGED PATIENTS
    |--------------------------------------------------------------------------
    */

    public function dischargedPatients($ward)
    {
        $ward = ucfirst(strtolower($ward));
        $discharged = Patient::getDischargedByWard($ward);

        usort($discharged, fn($a, $b) =>
            (int)$a->room_number - (int)$b->room_number
        );

        view('wards.discharged-patients', [
            'ward' => $ward,
            'discharged' => $discharged,
            'title' => $ward . ' Ward - Discharged Patients'
        ]);
    }
}