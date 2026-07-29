<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Config\Database;
use App\Models\ActivityLog;

class AdminController
{
    private $db;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->db = Database::getInstance();
    }

    // ==================== USER LIST ====================
    public function users()
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email, full_name, role, is_admin, is_active, created_at, last_login
            FROM users
            ORDER BY is_admin DESC, full_name ASC
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);
        view('admin/users', ['users' => $users]);
    }

    // ==================== TOGGLE ACTIVE ====================
    public function toggleActive()
    {
        header('Content-Type: application/json');
        $userId = (int)($_POST['user_id'] ?? 0);
        $active = (int)($_POST['is_active'] ?? 0);

        if (!$userId) { echo json_encode(['success' => false, 'error' => 'Invalid user']); exit; }

        // Prevent deactivating yourself
        if ($userId === (int)$_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot deactivate your own account']);
            exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        $result = $stmt->execute([$active, $userId]);

        if ($result) {
            $userStmt = $this->db->prepare("SELECT full_name, username FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch(\PDO::FETCH_OBJ);
            $name = $user->full_name ?? $user->username ?? 'Unknown';
            $action = $active ? 'activated' : 'deactivated';

            ActivityLog::create([
                'action_type' => 'user_updated',
                'description' => ($_SESSION['full_name'] ?? 'Admin') . " {$action} account for {$name}",
                'patient_id'  => null,
                'ward'        => null
            ]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update account']);
        }
        exit;
    }

    // ==================== TOGGLE ADMIN ====================
    public function toggleAdmin()
    {
        header('Content-Type: application/json');
        $userId = (int)($_POST['user_id'] ?? 0);
        $isAdmin = (int)($_POST['is_admin'] ?? 0);

        if (!$userId) { echo json_encode(['success' => false, 'error' => 'Invalid user']); exit; }

        if ($userId === (int)$_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot change your own admin status']);
            exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
        $result = $stmt->execute([$isAdmin, $userId]);

        if ($result) {
            $userStmt = $this->db->prepare("SELECT full_name, username FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch(\PDO::FETCH_OBJ);
            $name = $user->full_name ?? $user->username ?? 'Unknown';
            $action = $isAdmin ? 'promoted to admin' : 'removed admin rights from';

            ActivityLog::create([
                'action_type' => 'user_updated',
                'description' => ($_SESSION['full_name'] ?? 'Admin') . " {$action} {$name}",
                'patient_id'  => null,
                'ward'        => null
            ]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update admin status']);
        }
        exit;
    }

    // ==================== DELETE USER ====================
    public function deleteUser()
    {
        header('Content-Type: application/json');
        $userId = (int)($_POST['user_id'] ?? 0);

        if (!$userId) { echo json_encode(['success' => false, 'error' => 'Invalid user']); exit; }

        if ($userId === (int)$_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot delete your own account']);
            exit;
        }

        $userStmt = $this->db->prepare("SELECT full_name, username FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(\PDO::FETCH_OBJ);
        $name = $user->full_name ?? $user->username ?? 'Unknown';

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$userId]);

        if ($result) {
            ActivityLog::create([
                'action_type' => 'user_deleted',
                'description' => ($_SESSION['full_name'] ?? 'Admin') . " deleted account for {$name}",
                'patient_id'  => null,
                'ward'        => null
            ]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete account']);
        }
        exit;
    }

    // ==================== EDIT USER ====================
    public function editUser()
    {
        header('Content-Type: application/json');
        $userId   = (int)($_POST['user_id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $role     = trim($_POST['role'] ?? '');

        if (!$userId || !$fullName || !$role) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET full_name = ?, role = ? WHERE id = ?");
        $result = $stmt->execute([$fullName, $role, $userId]);

        if ($result) {
            ActivityLog::create([
                'action_type' => 'user_updated',
                'description' => ($_SESSION['full_name'] ?? 'Admin') . " updated account for {$fullName}",
                'patient_id'  => null,
                'ward'        => null
            ]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update account']);
        }
        exit;
    }
}