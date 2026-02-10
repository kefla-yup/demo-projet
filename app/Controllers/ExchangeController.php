<?php
namespace App\Controllers;

use Flight;
use App\Models\Objet;
use App\Models\Echange;

class ExchangeController {
    public static function index() {
        $echangeModel = new Echange();
        $echanges = $echangeModel->getByUser($_SESSION['user_id']);
        
        Flight::render('exchange/index', [
            'title' => 'Mes échanges - Takalo-takalo',
            'echanges' => $echanges
        ]);
    }
    
    public static function proposeForm($objetDemandeId) {
        $objetModel = new Objet();
        $objetDemande = $objetModel->findById($objetDemandeId);
        
        if (!$objetDemande) {
            Flight::notFound();
            return;
        }
        
        if ($objetDemande['user_id'] == $_SESSION['user_id']) {
            Flight::flash('error', 'Vous ne pouvez pas proposer un échange pour votre propre objet');
            Flight::redirect('/objets/' . $objetDemandeId);
            return;
        }
        
        $mesObjets = $objetModel->getByUser($_SESSION['user_id']);
        
        Flight::render('exchange/propose', [
            'title' => 'Proposer un échange - Takalo-takalo',
            'objetDemande' => $objetDemande,
            'mesObjets' => $mesObjets
        ]);
    }
    
    public static function propose() {
        $data = Flight::request()->data->getData();
        
        $objetModel = new Objet();
        $echangeModel = new Echange();
        
        // Vérifier que les objets existent et appartiennent aux bons utilisateurs
        $objetPropose = $objetModel->findById($data['objet_propose_id']);
        $objetDemande = $objetModel->findById($data['objet_demande_id']);
        
        if (!$objetPropose || !$objetDemande) {
            Flight::flash('error', 'Objet non trouvé');
            Flight::redirect('/objets');
            return;
        }
        
        if ($objetPropose['user_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Vous ne pouvez pas proposer un objet qui ne vous appartient pas');
            Flight::redirect('/objets/' . $data['objet_demande_id']);
            return;
        }
        
        if ($objetDemande['user_id'] == $_SESSION['user_id']) {
            Flight::flash('error', 'Vous ne pouvez pas proposer un échange pour votre propre objet');
            Flight::redirect('/objets/' . $data['objet_demande_id']);
            return;
        }
        
        $echangeData = [
            'objet_propose_id' => $data['objet_propose_id'],
            'objet_demande_id' => $data['objet_demande_id'],
            'proposeur_id' => $_SESSION['user_id'],
            'proprietaire_id' => $objetDemande['user_id'],
            'message' => $data['message'] ?? null
        ];
        
        if ($echangeModel->propose($echangeData)) {
            Flight::flash('success', 'Échange proposé avec succès !');
        } else {
            Flight::flash('error', 'Erreur lors de la proposition d\'échange');
        }
        
        Flight::redirect('/echanges');
    }
    
    public static function accept($echangeId) {
        $echangeModel = new Echange();
        $objetModel = new Objet();
        
        $echange = $echangeModel->findById($echangeId);
        
        if (!$echange || $echange['proprietaire_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Permission refusée');
            Flight::redirect('/echanges');
            return;
        }
        
        // Mettre à jour le statut de l'échange et transférer la propriété dans une transaction
        try {
            $db = Flight::db();
            $db->beginTransaction();

            if (!$echangeModel->updateStatus($echangeId, 'accepte')) {
                throw new \Exception('Impossible de mettre à jour le statut de l\'échange');
            }

            // Swap ownership: objet_propose (from proposeur) -> proprietaire; objet_demande -> proposeur
            $ok1 = $objetModel->transferOwnership($echange['objet_propose_id'], $echange['proprietaire_id']);
            $ok2 = $objetModel->transferOwnership($echange['objet_demande_id'], $echange['proposeur_id']);

            if (!($ok1 && $ok2)) {
                throw new \Exception('Erreur lors du transfert de propriété des objets');
            }

            $db->commit();
            Flight::flash('success', 'Échange accepté ! La propriété des objets a été transférée.');
        } catch (\Exception $ex) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
            Flight::flash('error', 'Erreur lors de l\'acceptation de l\'échange: ' . $ex->getMessage());
        }
        
        Flight::redirect('/echanges');
    }
    
    public static function refuse($echangeId) {
        $echangeModel = new Echange();
        $echange = $echangeModel->findById($echangeId);
        
        if (!$echange || $echange['proprietaire_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Permission refusée');
            Flight::redirect('/echanges');
            return;
        }
        
        if ($echangeModel->updateStatus($echangeId, 'refuse')) {
            Flight::flash('success', 'Échange refusé');
        } else {
            Flight::flash('error', 'Erreur lors du refus de l\'échange');
        }
        
        Flight::redirect('/echanges');
    }
    
    public static function cancel($echangeId) {
        $echangeModel = new Echange();
        $echange = $echangeModel->findById($echangeId);
        
        if (!$echange || $echange['proposeur_id'] != $_SESSION['user_id']) {
            Flight::flash('error', 'Permission refusée');
            Flight::redirect('/echanges');
            return;
        }
        
        if ($echangeModel->updateStatus($echangeId, 'annule')) {
            Flight::flash('success', 'Échange annulé');
        } else {
            Flight::flash('error', 'Erreur lors de l\'annulation de l\'échange');
        }
        
        Flight::redirect('/echanges');
    }
}
?>
