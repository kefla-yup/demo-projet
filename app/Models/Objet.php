<?php
namespace App\Models;

class Objet extends BaseModel {
    
    public function getAll() {
        $sql = "SELECT o.*, u.nom as proprietaire, c.nom as categorie
            FROM objets o
            JOIN users u ON o.user_id = u.id
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.est_disponible = 1
            ORDER BY o.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function searchByKeyword($q) {
        $sql = "SELECT o.*, u.nom as proprietaire, c.nom as categorie
            FROM objets o
            JOIN users u ON o.user_id = u.id
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.est_disponible = 1 AND (o.nom LIKE ? OR o.description LIKE ?)
            ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $q . '%';
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function searchByKeywordAndCategory($q, $categorieId) {
        $sql = "SELECT o.*, u.nom as proprietaire, c.nom as categorie
            FROM objets o
            JOIN users u ON o.user_id = u.id
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.est_disponible = 1 AND o.categorie_id = ? AND (o.nom LIKE ? OR o.description LIKE ?)
            ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $q . '%';
        $stmt->execute([$categorieId, $like, $like]);
        return $stmt->fetchAll();
    }
    
    public function getByUser($userId) {
        $sql = "SELECT o.*, c.nom as categorie
            FROM objets o
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT o.*, u.nom as proprietaire, u.email, u.telephone, c.nom as categorie
            FROM objets o
            JOIN users u ON o.user_id = u.id
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $objet = $stmt->fetch();
        if ($objet) {
            $objet['photos'] = $this->getPhotos($id);
        }
        return $objet;
    }
    
    public function create($data) {
        $sql = "INSERT INTO objets (nom, description, prix_estime, categorie_id, user_id, photo)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['prix_estime'] ?? null,
            $data['categorie_id'],
            $data['user_id'],
            $data['photo'] ?? null
        ]);
        if ($res) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    public function update($id, $data) {
        $sql = "UPDATE objets SET nom = ?, description = ?, categorie_id = ?, photo = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['categorie_id'],
            $data['photo'] ?? null,
            $id
        ]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM objets WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function addPhoto($objetId, $filename) {
        $sql = "INSERT INTO object_photos (objet_id, filename) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$objetId, $filename]);
    }

    public function getPhotos($objetId) {
        $sql = "SELECT filename FROM object_photos WHERE objet_id = ? ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$objetId]);
        $rows = $stmt->fetchAll();
        $photos = [];
        foreach ($rows as $r) $photos[] = $r['filename'];
        return $photos;
    }
    
    public function updateDisponibility($id, $disponible) {
        $sql = "UPDATE objets SET est_disponible = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        // Ensure we pass an integer (0 or 1) to the DB to avoid invalid value errors
        $val = ($disponible === null) ? 0 : ((bool)$disponible ? 1 : 0);
        return $stmt->execute([$val, $id]);
    }

    public function transferOwnership($objetId, $newOwnerId) {
        $sql = "UPDATE objets SET user_id = ?, est_disponible = 0 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newOwnerId, $objetId]);
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY nom";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getByCategorie($categorieId) {
        $sql = "SELECT o.*, u.nom as proprietaire, c.nom as categorie
            FROM objets o
            JOIN users u ON o.user_id = u.id
            JOIN categories c ON o.categorie_id = c.id
            WHERE o.est_disponible = 1 AND o.categorie_id = ?
            ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categorieId]);
        return $stmt->fetchAll();
    }
}
?>
