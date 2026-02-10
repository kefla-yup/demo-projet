<?php
namespace App\Models;

class Echange extends BaseModel {

    public function propose($data) {
        $sql = "INSERT INTO echanges (objet_propose_id, objet_demande_id, proposeur_id, proprietaire_id, message)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['objet_propose_id'],
            $data['objet_demande_id'],
            $data['proposeur_id'],
            $data['proprietaire_id'],
            $data['message'] ?? null
        ]);
    }

    public function getByUser($userId) {
        $sql = "SELECT e.*,
                op.nom as objet_propose_nom, op.photo as objet_propose_photo,
                od.nom as objet_demande_nom, od.photo as objet_demande_photo,
                p.nom as proposeur_nom, pr.nom as proprietaire_nom
                FROM echanges e
                JOIN objets op ON e.objet_propose_id = op.id
                JOIN objets od ON e.objet_demande_id = od.id
                JOIN users p ON e.proposeur_id = p.id
                JOIN users pr ON e.proprietaire_id = pr.id
                WHERE e.proposeur_id = ? OR e.proprietaire_id = ?
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT e.*,
                op.user_id as objet_propose_owner, op.nom as objet_propose_nom,
                od.user_id as objet_demande_owner, od.nom as objet_demande_nom
                FROM echanges e
                JOIN objets op ON e.objet_propose_id = op.id
                JOIN objets od ON e.objet_demande_id = od.id
                WHERE e.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE echanges SET statut = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getPropositionsForObject($objetId) {
        $sql = "SELECT e.*,
                op.nom as objet_propose_nom, op.photo as objet_propose_photo,
                u.nom as proposeur_nom
                FROM echanges e
                JOIN objets op ON e.objet_propose_id = op.id
                JOIN users u ON e.proposeur_id = u.id
                WHERE e.objet_demande_id = ? AND e.statut = 'en_attente'
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$objetId]);
        return $stmt->fetchAll();
    }
}
?>
