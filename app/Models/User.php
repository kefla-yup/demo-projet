<?php
namespace App\Models;

class User extends BaseModel {
    
    public function create($data) {
        $sql = "INSERT INTO users (nom, email, password, telephone, adresse, is_admin) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['telephone'] ?? null,
            $data['adresse'] ?? null,
            $data['is_admin'] ?? 0
        ]);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE users SET nom = ?, telephone = ?, adresse = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }
    
    public function getAll() {
        $sql = "SELECT id, nom, email, created_at FROM users ORDER BY nom";
        return $this->db->query($sql)->fetchAll();
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as c FROM users";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row ? (int)$row['c'] : 0;
    }
}
?>
