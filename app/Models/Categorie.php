<?php
namespace App\Models;

class Categorie extends BaseModel {
    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY nom";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO categories (nom) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nom']]);
    }

    public function update($id, $data) {
        $sql = "UPDATE categories SET nom = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nom'], $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
