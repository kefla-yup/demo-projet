<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h2 mb-4">Mes échanges</h1>
    
    <div class="row">
        <!-- Échanges reçus -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Demandes reçues</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $echangesRecus = array_filter($echanges, function($e) {
                        return $e['proprietaire_id'] == $_SESSION['user_id'] && $e['statut'] == 'en_attente';
                    });
                    ?>
                    
                    <?php if(empty($echangesRecus)): ?>
                        <p class="text-muted">Aucune demande d'échange reçue.</p>
                    <?php else: ?>
                        <?php foreach($echangesRecus as $echange): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($echange['objet_propose_nom']); ?></h6>
                                    <p class="card-text small text-muted">
                                        <strong>Contre :</strong> <?php echo htmlspecialchars($echange['objet_demande_nom']); ?><br>
                                        <strong>De :</strong> <?php echo htmlspecialchars($echange['proposeur_nom']); ?><br>
                                        <strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($echange['created_at'])); ?>
                                    </p>
                                    <div class="btn-group btn-group-sm">
                                        <form action="/echanges/<?php echo $echange['id']; ?>/accepter" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accepter cet échange ?')">
                                                Accepter
                                            </button>
                                        </form>
                                        <form action="/echanges/<?php echo $echange['id']; ?>/refuser" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Refuser cet échange ?')">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Échanges envoyés -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Demandes envoyées</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $echangesEnvoyes = array_filter($echanges, function($e) {
                        return $e['proposeur_id'] == $_SESSION['user_id'];
                    });
                    ?>
                    
                    <?php if(empty($echangesEnvoyes)): ?>
                        <p class="text-muted">Vous n'avez envoyé aucune demande d'échange.</p>
                    <?php else: ?>
                        <?php foreach($echangesEnvoyes as $echange): ?>
                            <div class="card mb-3 border-<?php 
                                switch($echange['statut']) {
                                    case 'en_attente': echo 'warning'; break;
                                    case 'accepte': echo 'success'; break;
                                    case 'refuse': echo 'danger'; break;
                                    case 'annule': echo 'secondary'; break;
                                }
                            ?>">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($echange['objet_propose_nom']); ?></h6>
                                    <p class="card-text small text-muted">
                                        <strong>Contre :</strong> <?php echo htmlspecialchars($echange['objet_demande_nom']); ?><br>
                                        <strong>À :</strong> <?php echo htmlspecialchars($echange['proprietaire_nom']); ?><br>
                                        <strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($echange['created_at'])); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-<?php 
                                            switch($echange['statut']) {
                                                case 'en_attente': echo 'warning'; break;
                                                case 'accepte': echo 'success'; break;
                                                case 'refuse': echo 'danger'; break;
                                                case 'annule': echo 'secondary'; break;
                                            }
                                        ?>">
                                            <?php 
                                            switch($echange['statut']) {
                                                case 'en_attente': echo 'En attente'; break;
                                                case 'accepte': echo 'Accepté'; break;
                                                case 'refuse': echo 'Refusé'; break;
                                                case 'annule': echo 'Annulé'; break;
                                            }
                                            ?>
                                        </span>
                                        
                                        <?php if($echange['statut'] == 'en_attente'): ?>
                                            <form action="/echanges/<?php echo $echange['id']; ?>/annuler" method="POST" class="d-inline">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Annuler cet échange ?')">
                                                    Annuler
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique des échanges -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historique des échanges terminés</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $echangesTermines = array_filter($echanges, function($e) {
                        return $e['statut'] != 'en_attente';
                    });
                    ?>
                    
                    <?php if(empty($echangesTermines)): ?>
                        <p class="text-muted">Aucun échange terminé pour le moment.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Objet donné</th>
                                        <th>Objet reçu</th>
                                        <th>Avec</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($echangesTermines as $echange): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($echange['objet_propose_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($echange['objet_demande_nom']); ?></td>
                                        <td>
                                            <?php 
                                            if ($echange['proposeur_id'] == $_SESSION['user_id']) {
                                                echo htmlspecialchars($echange['proprietaire_nom']);
                                            } else {
                                                echo htmlspecialchars($echange['proposeur_nom']);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                switch($echange['statut']) {
                                                    case 'accepte': echo 'success'; break;
                                                    case 'refuse': echo 'danger'; break;
                                                    case 'annule': echo 'secondary'; break;
                                                }
                                            ?>">
                                                <?php 
                                                switch($echange['statut']) {
                                                    case 'accepte': echo 'Accepté'; break;
                                                    case 'refuse': echo 'Refusé'; break;
                                                    case 'annule': echo 'Annulé'; break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($echange['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>