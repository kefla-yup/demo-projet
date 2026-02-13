<?php
namespace App\Controllers;

use Flight;
use App\Models\Categorie;
use App\Models\User;

class CategoryController {
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

    public static function index() {
        if (!self::checkAdmin()) return;
        $cat = new Categorie();
        $categories = $cat->getAll();
        Flight::render('category/index', [
            'title' => 'Gestion des catégories',
            'categories' => $categories
        ]);
    }

    public static function create() {
        if (!self::checkAdmin()) return;
        Flight::render('category/create', [
            'title' => 'Nouvelle catégorie'
        ]);
    }

    public static function store() {
        if (!self::checkAdmin()) return;
        $data = Flight::request()->data->getData();
        $cat = new Categorie();
        if (empty($data['nom'])) {
            Flight::flash('error', 'Le nom est requis');
            Flight::redirect('/admin/categories/nouveau');
            return;
        }
        if ($cat->create($data)) {
            Flight::flash('success', 'Catégorie ajoutée');
        } else {
            Flight::flash('error', 'Erreur lors de la création');
        }
        Flight::redirect('/admin/categories');
    }

    public static function edit($id) {
        if (!self::checkAdmin()) return;
        $cat = new Categorie();
        $categorie = $cat->findById($id);
        if (!$categorie) {
            Flight::notFound();
            return;
        }
        Flight::render('category/edit', [
            'title' => 'Modifier catégorie',
            'categorie' => $categorie
        ]);
    }

    public static function update($id) {
        if (!self::checkAdmin()) return;
        $data = Flight::request()->data->getData();
        $cat = new Categorie();
        if (empty($data['nom'])) {
            Flight::flash('error', 'Le nom est requis');
            Flight::redirect('/admin/categories/' . $id . '/editer');
            return;
        }
        if ($cat->update($id, $data)) {
            Flight::flash('success', 'Catégorie mise à jour');
        } else {
            Flight::flash('error', 'Erreur lors de la mise à jour');
        }
        Flight::redirect('/admin/categories');
    }

    public static function delete($id) {
        if (!self::checkAdmin()) return;
        $cat = new Categorie();
        if ($cat->delete($id)) {
            Flight::flash('success', 'Catégorie supprimée');
        } else {
            Flight::flash('error', 'Erreur lors de la suppression');
        }
        Flight::redirect('/admin/categories');
    }
}
