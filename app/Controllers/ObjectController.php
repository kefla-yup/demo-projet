<?php
namespace App\Controllers;

use Flight;
use App\Models\Objet;

class ObjectController {
    public static function index() {
        $objetModel = new Objet();
        $categories = $objetModel->getCategories();
        
        $categorieId = Flight::request()->query->categorie_id;
        $objets = $categorieId 
            ? $objetModel->getByCategorie($categorieId)
            : $objetModel->getAll();
        
        Flight::render('objet/index', [
            'title' => 'Objets disponibles - Takalo-takalo',
            'objets' => $objets,
            'categories' => $categories,
            'selectedCategorie' => $categorieId
        ]);
    }
    
    public static function show($id) {
        $objetModel = new Objet();
        $objet = $objetModel->findById($id);
        
        if (!$objet) {
            Flight::notFound();
            return;
        }
        
        // Objets de l'utilisateur connecté pour proposition d'échange
        $mesObjets = [];
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $objet['user_id']) {
            $mesObjets = $objetModel->getByUser($_SESSION['user_id']);
        }
        
        Flight::render('objet/show', [
            'title' => $objet['nom'] . ' - Takalo-takalo',
            'objet' => $objet,
            'mesObjets' => $mesObjets
        ]);
    }
    
    public static function create() {
        $objetModel = new Objet();
        $categories = $objetModel->getCategories();
        
        Flight::render('objet/create', [
            'title' => 'Ajouter un objet - Takalo-takalo',
            'categories' => $categories
        ]);
    }
    
    public static function store() {
        $data = Flight::request()->data->getData();
        @file_put_contents(__DIR__ . '/../../objet_debug.txt', "STORE CALL\nDATA:" . var_export($data, true) . "\nFILES:" . var_export(
            isset($_FILES) ? $_FILES : null, true
        ) . "\nSESSION:" . var_export(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null, true) . "\n---\n", FILE_APPEND);
        
        // Validation
        $errors = [];
        if (empty($data['nom'])) $errors[] = 'Le nom est requis';
        if (empty($data['description'])) $errors[] = 'La description est requise';
        if (empty($data['categorie_id'])) $errors[] = 'La catégorie est requise';
        
        if (!empty($errors)) {
            Flight::flash('error', implode('<br>', $errors));
            Flight::redirect('/objets/nouveau');
            return;
        }
        
        // Gestion de l'upload de photo
        $photoName = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($ext, $allowedTypes)) {
                $photoName = uniqid() . '.' . $ext;
                $uploadPath = Flight::get('app.uploads') . $photoName;
                
                if (!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    Flight::flash('error', 'Erreur lors de l\'upload de la photo');
                    Flight::redirect('/objets/nouveau');
                    return;
                }
            }
        }
        
        $objetModel = new Objet();
        
        $objetData = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'categorie_id' => $data['categorie_id'],
            'user_id' => $_SESSION['user_id'],
            'photo' => $photoName
        ];
        
        if ($objetModel->create($objetData)) {
            Flight::flash('success', 'Objet ajouté avec succès !');
            Flight::redirect('/dashboard');
        } else {
                @file_put_contents(__DIR__ . '/../../objet_debug.txt', "CREATE FAILED: " . var_export($objetData, true) . "\n", FILE_APPEND);
            Flight::flash('error', 'Erreur lors de l\'ajout de l\'objet');
            Flight::redirect('/objets/nouveau');
        }
    }
    
    public static function edit($id) {
        $objetModel = new Objet();
        $objet = $objetModel->findById($id);
        
        if (!$objet || $objet['user_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Vous n\'avez pas la permission de modifier cet objet');
            Flight::redirect('/dashboard');
            return;
        }
        
        $categories = $objetModel->getCategories();
        
        Flight::render('objet/edit', [
            'title' => 'Modifier ' . $objet['nom'] . ' - Takalo-takalo',
            'objet' => $objet,
            'categories' => $categories
        ]);
    }
    
    public static function update($id) {
        $data = Flight::request()->data->getData();
        
        $objetModel = new Objet();
        $objet = $objetModel->findById($id);
        
        if (!$objet || $objet['user_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Permission refusée');
            Flight::redirect('/dashboard');
            return;
        }
        
        // Validation
        $errors = [];
        if (empty($data['nom'])) $errors[] = 'Le nom est requis';
        if (empty($data['description'])) $errors[] = 'La description est requise';
        if (empty($data['categorie_id'])) $errors[] = 'La catégorie est requise';
        
        if (!empty($errors)) {
            Flight::flash('error', implode('<br>', $errors));
            Flight::redirect('/objets/' . $id . '/editer');
            return;
        }
        
        $updateData = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'categorie_id' => $data['categorie_id']
        ];
        
        // Gestion de l'upload de photo si nouvelle photo fournie
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($ext, $allowedTypes)) {
                $photoName = uniqid() . '.' . $ext;
                $uploadPath = Flight::get('app.uploads') . $photoName;
                
                if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($objet['photo']) {
                        $oldPhotoPath = Flight::get('app.uploads') . $objet['photo'];
                        if (file_exists($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                    }
                    $updateData['photo'] = $photoName;
                }
            }
        }
        
        if ($objetModel->update($id, $updateData)) {
            Flight::flash('success', 'Objet modifié avec succès !');
            Flight::redirect('/objets/' . $id);
        } else {
            Flight::flash('error', 'Erreur lors de la modification de l\'objet');
            Flight::redirect('/objets/' . $id . '/editer');
        }
    }
    
    public static function delete($id) {
        $objetModel = new Objet();
        $objet = $objetModel->findById($id);
        
        if (!$objet || $objet['user_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Permission refusée');
            Flight::redirect('/dashboard');
            return;
        }
        
        // Supprimer la photo si elle existe
        if ($objet['photo']) {
            $photoPath = Flight::get('app.uploads') . $objet['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
        
        if ($objetModel->delete($id)) {
            Flight::flash('success', 'Objet supprimé avec succès');
        } else {
            Flight::flash('error', 'Erreur lors de la suppression de l\'objet');
        }
        
        Flight::redirect('/dashboard');
    }
}
?>
