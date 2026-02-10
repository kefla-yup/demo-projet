<?php
namespace App\Config;

use Flight;

class Routes {
    public static function register() {
        // Routes publiques
        Flight::route('/', ['App\Controllers\HomeController', 'index']);
        Flight::route('GET /register', ['App\Controllers\AuthController', 'showRegisterForm']);
        Flight::route('POST /register', ['App\Controllers\AuthController', 'register']);
        Flight::route('GET /login', ['App\Controllers\AuthController', 'showLoginForm']);
        Flight::route('POST /login', ['App\Controllers\AuthController', 'login']);
        Flight::route('/objets', ['App\Controllers\ObjectController', 'index']);
        // test endpoint to invoke store directly (temporary)
        Flight::route('/_test_store', ['App\Controllers\ObjectController', 'store']);
        // 'nouveau' must be registered before the dynamic @id route so it's not
        // interpreted as an object id.
        Flight::route('GET /objets/nouveau', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ObjectController', 'create']);
        });

        Flight::route('POST /objets/nouveau', ['App\Controllers\ObjectController', 'store']);

        Flight::route('/objets/@id', ['App\Controllers\ObjectController', 'show']);
        
        // Routes protégées (nécessitent connexion)
        Flight::route('/dashboard', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\UserController', 'dashboard']);
        });
        
        Flight::route('/profile', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\UserController', 'profile']);
        });
        
        Flight::route('POST /profile', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\UserController', 'updateProfile']);
        });
        
        
        
        Flight::route('/objets/@id/editer', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ObjectController', 'edit'], $id);
        });
        
        Flight::route('POST /objets/@id/editer', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ObjectController', 'update'], $id);
        });
        
        Flight::route('POST /objets/@id/supprimer', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ObjectController', 'delete'], $id);
        });
        
        Flight::route('/echanges', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'index']);
        });
        
        Flight::route('/echanges/proposer/@id', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'proposeForm'], $id);
        });
        
        Flight::route('POST /echanges/proposer', function() {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'propose']);
        });
        
        Flight::route('POST /echanges/@id/accepter', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'accept'], $id);
        });
        
        Flight::route('POST /echanges/@id/refuser', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'refuse'], $id);
        });
        
        Flight::route('POST /echanges/@id/annuler', function($id) {
            if (!isset($_SESSION['user_id'])) {
                Flight::redirect('/login');
                return;
            }
            call_user_func(['App\Controllers\ExchangeController', 'cancel'], $id);
        });
        
        Flight::route('/logout', ['App\Controllers\AuthController', 'logout']);
        
        // Route 404
        Flight::map('notFound', function() {
            Flight::render('errors/404', ['title' => 'Page non trouvée']);
        });
    }
}
?>
