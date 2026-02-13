<?php
namespace App\Controllers;

use Flight;
use App\Models\User;
use App\Models\Echange;

class AdminController {
    protected static function checkAdmin() {
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return false;
        }
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        if (!$user || empty($user['is_admin'])) {
            Flight::flash('error', 'Accès refusé');
            Flight::redirect('/');
            return false;
        }
        return true;
    }

    public static function dashboard() {
        if (!self::checkAdmin()) return;
        $userModel = new User();
        $echangeModel = new Echange();

        $usersCount = $userModel->countAll();
        $echangesCount = $echangeModel->countAll();

        Flight::render('admin/dashboard', [
            'title' => 'Admin - Tableau de bord',
            'usersCount' => $usersCount,
            'echangesCount' => $echangesCount
        ]);
    }
}
