<?php
namespace App\Controllers;

use Flight;
use App\Models\Objet;

class ObjectController {
    public static function index() {
        $objetModel = new Objet();
        $categories = $objetModel->getCategories();

        $request = Flight::request();
        $categorieId = $request->query->categorie_id;
        $q = trim($request->query->q ?? '');

        if (!empty($q) && !empty($categorieId)) {
            $objets = $objetModel->searchByKeywordAndCategory($q, $categorieId);
        } elseif (!empty($q)) {
            $objets = $objetModel->searchByKeyword($q);
        } elseif (!empty($categorieId)) {
            $objets = $objetModel->getByCategorie($categorieId);
        } else {
            $objets = $objetModel->getAll();
        }
        
        Flight::render('objet/index', [
            'title' => 'Objets disponibles - Takalo-takalo',
            'objets' => $objets,
            'categories' => $categories,
            'selectedCategorie' => $categorieId,
            'q' => $q
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
        
        // Ownership history events
        $echangeModel = new \App\Models\Echange();
        $historyEvents = $echangeModel->getAcceptedEventsForObject($id);
        $ownershipTimeline = [];
        foreach ($historyEvents as $ev) {
            if ($ev['objet_propose_id'] == $id) {
                // object was proposed by proposeur -> transferred to proprietaire
                $from = $ev['proposeur_nom'];
                $to = $ev['proprietaire_nom'];
            } else {
                // object was demanded -> transferred from proprietaire to proposeur
                $from = $ev['proprietaire_nom'];
                $to = $ev['proposeur_nom'];
            }
            $ownershipTimeline[] = [
                'date' => $ev['created_at'],
                'from' => $from,
                'to' => $to,
                'message' => $ev['message'] ?? null
            ];
        }
        
        Flight::render('objet/show', [
            'title' => $objet['nom'] . ' - Takalo-takalo',
            'objet' => $objet,
            'mesObjets' => $mesObjets,
            'ownershipTimeline' => $ownershipTimeline
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
        
        // Gestion de l'upload de plusieurs photos (input name="photos[]")
        $uploadedFiles = [];
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (isset($_FILES['photos'])) {
            $files = $_FILES['photos'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowedTypes)) {
                        $photoName = uniqid() . '.' . $ext;
                        $uploadPath = Flight::get('app.uploads') . $photoName;
                        if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                            $uploadedFiles[] = $photoName;
                        }
                    }
                }
            }
        } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Backwards compatibility with single photo input
            $photo = $_FILES['photo'];
            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowedTypes)) {
                $photoName = uniqid() . '.' . $ext;
                $uploadPath = Flight::get('app.uploads') . $photoName;
                if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    $uploadedFiles[] = $photoName;
                }
            }
        }

        $objetModel = new Objet();

        $objetData = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'prix_estime' => isset($data['prix_estime']) ? $data['prix_estime'] : null,
            'categorie_id' => $data['categorie_id'],
            'user_id' => $_SESSION['user_id'],
            'photo' => count($uploadedFiles) ? $uploadedFiles[0] : null
        ];

        $newId = $objetModel->create($objetData);
        if ($newId) {
            // Enregistrer toutes les photos en base
            foreach ($uploadedFiles as $f) {
                $objetModel->addPhoto($newId, $f);
            }
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
