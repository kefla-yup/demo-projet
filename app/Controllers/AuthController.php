<?php
namespace App\Controllers;

use Flight;
use App\Models\User;

class AuthController {
    public static function showRegisterForm() {
        Flight::render('auth/register', [
            'title' => 'Inscription - Takalo-takalo'
        ]);
    }
    
    public static function register() {
        $data = Flight::request()->data->getData();
        
        // Validation
        $errors = [];
        if (empty($data['nom'])) $errors[] = 'Le nom est requis';
        if (empty($data['email'])) $errors[] = 'L\'email est requis';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
        if (empty($data['password'])) $errors[] = 'Le mot de passe est requis';
        if (strlen($data['password']) < 6) $errors[] = 'Le mot de passe doit faire au moins 6 caractères';
        
        if (!empty($errors)) {
            Flight::render('auth/register', [
                'title' => 'Inscription - Takalo-takalo',
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        $userModel = new User();
        
        // Vérifier si l'email existe déjà
        if ($userModel->findByEmail($data['email'])) {
            Flight::render('auth/register', [
                'title' => 'Inscription - Takalo-takalo',
                'errors' => ['Cet email est déjà utilisé'],
                'data' => $data
            ]);
            return;
        }
        
        // Créer l'utilisateur
        if ($userModel->create($data)) {
            Flight::flash('success', 'Inscription réussie ! Connectez-vous maintenant.');
            Flight::redirect('/login');
        } else {
            Flight::render('auth/register', [
                'title' => 'Inscription - Takalo-takalo',
                'errors' => ['Une erreur est survenue lors de l\'inscription'],
                'data' => $data
            ]);
        }
    }
    
    public static function showLoginForm() {
        Flight::render('auth/login', [
            'title' => 'Connexion - Takalo-takalo'
        ]);
    }
    
    public static function login() {
        $data = Flight::request()->data->getData();
        // debug: log posted data
        @file_put_contents(__DIR__ . '/../../login_debug.txt', var_export($data, true) . "\n", FILE_APPEND);
        
        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);
        @file_put_contents(__DIR__ . '/../../login_debug.txt', "USER:" . var_export($user, true) . "\n", FILE_APPEND);
        @file_put_contents(__DIR__ . '/../../login_debug.txt', "VERIFY:" . (isset($user['password']) ? (password_verify($data['password'], $user['password']) ? '1' : '0') : 'no-pass') . "\n", FILE_APPEND);
        
        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_is_admin'] = !empty($user['is_admin']);
            
            Flight::flash('success', 'Connexion réussie !');
            Flight::redirect('/dashboard');
        } else {
            Flight::render('auth/login', [
                'title' => 'Connexion - Takalo-takalo',
                'errors' => ['Email ou mot de passe incorrect'],
                'email' => $data['email']
            ]);
        }
    }
    
    public static function logout() {
        session_destroy();
        Flight::flash('success', 'Vous avez été déconnecté.');
        Flight::redirect('/');
    }
}
?>
