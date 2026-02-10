<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h2 mb-4">Tableau de bord</h1>
    
    <!-- Stats -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mes objets</h5>
                    <p class="card-text display-6"><?php echo count($mesObjets); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Échanges en cours</h5>
                    <?php 
                    $echangesEnCours = array_filter($mesEchanges, function($e) {
                        return $e['statut'] == 'en_attente';
                    });
                    ?>
                    <p class="card-text display-6"><?php echo count($echangesEnCours); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Échanges réussis</h5>
                    <?php 
                    $echangesReussis = array_filter($mesEchanges, function($e) {
                        return $e['statut'] == 'accepte';
                    });
                    ?>
                    <p class="card-text display-6"><?php echo count($echangesReussis); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mes objets -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes objets</h5>
                    <a href="/objets/nouveau" class="btn btn-sm btn-primary">Ajouter un objet</a>
                </div>
                <div class="card-body">
                    <?php if(empty($mesObjets)): ?>
                        <p class="text-muted">Vous n'avez pas encore d'objets. <a href="/objets/nouveau">Ajoutez-en un !</a></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Date d'ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($mesObjets as $objet): ?>
                                    <tr>
                                        <td>
                                            <?php if($objet['photo']): ?>
                                                <img src="/uploads/<?php echo htmlspecialchars($objet['photo']); ?>" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($objet['nom']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($objet['categorie']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($objet['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/objets/<?php echo $objet['id']; ?>" class="btn btn-outline-primary">Voir</a>
                                                <a href="/objets/<?php echo $objet['id']; ?>/editer" class="btn btn-outline-warning">Modifier</a>
                                                <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Supprimer cet objet ?')) { document.getElementById('delete-form-<?php echo $objet['id']; ?>').submit(); }">
                                                    Supprimer
                                                </button>
                                                <form id="delete-form-<?php echo $objet['id']; ?>" action="/objets/<?php echo $objet['id']; ?>/supprimer" method="POST" class="d-none"></form>
                                            </div>
                                        </td>
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
    
    <!-- Mes échanges récents -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mes échanges récents</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($mesEchanges)): ?>
                        <p class="text-muted">Vous n'avez pas encore d'échanges.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Objet proposé</th>
                                        <th>Objet demandé</th>
                                        <th>Avec</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($mesEchanges as $echange): ?>
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
                                            <?php
                                            $statutClasses = [
                                                'en_attente' => 'warning',
                                                'accepte' => 'success',
                                                'refuse' => 'danger',
                                                'annule' => 'secondary'
                                            ];
                                            $statutTexts = [
                                                'en_attente' => 'En attente',
                                                'accepte' => 'Accepté',
                                                'refuse' => 'Refusé',
                                                'annule' => 'Annulé'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $statutClasses[$echange['statut']]; ?>">
                                                <?php echo $statutTexts[$echange['statut']]; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($echange['created_at'])); ?></td>
                                        <td>
                                            <a href="/echanges" class="btn btn-sm btn-outline-primary">Gérer</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/echanges" class="btn btn-primary">Voir tous mes échanges</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>