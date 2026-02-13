<?php
namespace App\Controllers;

use Flight;
use App\Models\User;
use App\Models\Objet;
use App\Models\Echange;

class UserController {
    public static function dashboard() {
        try {
            $objetModel = new Objet();
            $echangeModel = new Echange();

            $mesObjets = $objetModel->getByUser($_SESSION['user_id']);
            $mesEchanges = $echangeModel->getByUser($_SESSION['user_id']);

            Flight::render('user/dashboard', [
                'title' => 'Tableau de bord - Takalo-takalo',
                'mesObjets' => $mesObjets,
                'mesEchanges' => $mesEchanges
            ]);
        } catch (\Throwable $e) {
            // debug: previously logged dashboard errors to login_debug.txt (removed)
            Flight::render('errors/404', ['title' => 'Erreur interne']);
        }
    }
    
    public static function profile() {
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        Flight::render('user/profile', [
            'title' => 'Mon profil - Takalo-takalo',
            'user' => $user
        ]);
    }
    
    public static function updateProfile() {
        $data = Flight::request()->data->getData();
        
        $userModel = new User();
        
        if ($userModel->update($_SESSION['user_id'], $data)) {
            $_SESSION['user_nom'] = $data['nom'];
            Flight::flash('success', 'Profil mis à jour avec succès !');
        } else {
            Flight::flash('error', 'Erreur lors de la mise à jour du profil');
        }
        
        Flight::redirect('/profile');
    }
}
?>
