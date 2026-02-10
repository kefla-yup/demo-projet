<?php
// Charger l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
session_start();

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/../app/views');
Flight::set('flight.log_errors', true);
Flight::set('flight.base_url', '/');

// Charger la configuration de la base de données
$dbConfig = require __DIR__ . '/../app/Config/database.php';

// Connexion à la base de données
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Enregistrer la connexion PDO dans Flight
    Flight::register('db', 'PDO', array($dsn, $dbConfig['username'], $dbConfig['password']));
    
    // Définir le fuseau horaire
    $pdo->exec("SET time_zone = '+03:00'");
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Configuration des chemins
Flight::set('app.root', realpath(__DIR__ . '/../'));
Flight::set('app.public', __DIR__);
Flight::set('app.uploads', __DIR__ . '/uploads/');

// Helper pour les messages flash
if (!function_exists('flash')) {
    function flash($type, $message) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type][] = $message;
    }
}

// Mapper Flight::flash sur le helper global pour compatibilité
Flight::map('flash', function($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type][] = $message;
});

// Helper pour afficher les messages flash
Flight::map('displayFlash', function() {
    if (!isset($_SESSION['flash'])) {
        return '';
    }
    
    $html = '';
    foreach ($_SESSION['flash'] as $type => $messages) {
        $alertClass = $type === 'error' ? 'danger' : $type;
        foreach ($messages as $message) {
            $html .= '<div class="alert alert-' . $alertClass . ' alert-dismissible fade show" role="alert">';
            $html .= htmlspecialchars($message);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            $html .= '</div>';
        }
    }
    
    unset($_SESSION['flash']);
    return $html;
});

// Charger les routes
require_once __DIR__ . '/../app/Config/Routes.php';
App\Config\Routes::register();

// Démarrer l'application
Flight::start();