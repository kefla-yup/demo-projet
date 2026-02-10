<?php
namespace App\Controllers;

use Flight;
use App\Models\Objet;

class HomeController {
    public static function index() {
        $objetModel = new Objet();
        $objets = $objetModel->getAll();
        
        Flight::render('home/index', [
            'title' => 'Takalo-takalo - Échangez vos objets',
            'objets' => $objets
        ]);
    }
}
?>
