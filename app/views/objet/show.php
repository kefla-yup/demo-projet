<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/objets">Objets</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($objet['nom']); ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Image de l'objet -->
        <div class="col-md-6">
            <?php if(!empty($objet['photos'])): ?>
                <div class="mb-3">
                    <img id="mainImage" src="/uploads/<?php echo htmlspecialchars($objet['photos'][0]); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($objet['nom']); ?>">
                </div>
                <div class="d-flex flex-wrap">
                    <?php foreach($objet['photos'] as $p): ?>
                        <img src="/uploads/<?php echo htmlspecialchars($p); ?>" class="img-thumbnail me-2 mb-2" style="height:80px;cursor:pointer;" onclick="document.getElementById('mainImage').src=this.src">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <img src="/assets/img/no-image.jpg" class="img-fluid rounded shadow" alt="Pas d'image">
            <?php endif; ?>
        </div>
        
        <!-- Détails de l'objet -->
        <div class="col-md-6">
            <h1 class="h2"><?php echo htmlspecialchars($objet['nom']); ?></h1>
            <div class="mb-3">
                <span class="badge bg-primary"><?php echo htmlspecialchars($objet['categorie']); ?></span>
            </div>
            
            <p class="lead"><?php echo nl2br(htmlspecialchars($objet['description'])); ?></p>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Propriétaire</h5>
                    <p class="card-text">
                        <strong><?php echo htmlspecialchars($objet['proprietaire']); ?></strong><br>
                        <?php if($objet['email']): ?>
                            <i class='bx bx-envelope'></i> <?php echo htmlspecialchars($objet['email']); ?><br>
                        <?php endif; ?>
                        <?php if($objet['telephone']): ?>
                            <i class='bx bx-phone'></i> <?php echo htmlspecialchars($objet['telephone']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Historique d'appartenance</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($ownershipTimeline)): ?>
                        <p class="text-muted">Aucun échange accepté pour cet objet — pas d'historique de transfert.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach($ownershipTimeline as $ev): ?>
                                <li class="list-group-item">
                                    <strong><?php echo date('d/m/Y H:i', strtotime($ev['date'])); ?></strong>
                                    &nbsp;: <em><?php echo htmlspecialchars($ev['from']); ?></em>
                                    &nbsp;→&nbsp; <em><?php echo htmlspecialchars($ev['to']); ?></em>
                                    <?php if(!empty($ev['message'])): ?>
                                        <div class="small text-muted">Message: <?php echo htmlspecialchars($ev['message']); ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="d-grid gap-2">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['user_id'] == $objet['user_id']): ?>
                        <!-- Propriétaire de l'objet -->
                        <div class="btn-group w-100" role="group">
                            <a href="/objets/<?php echo $objet['id']; ?>/editer" class="btn btn-warning">Modifier</a>
                            <button type="button" class="btn btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) { document.getElementById('delete-form').submit(); }">
                                Supprimer
                            </button>
                            <form id="delete-form" action="/objets/<?php echo $objet['id']; ?>/supprimer" method="POST" class="d-none"></form>
                        </div>
                    <?php else: ?>
                        <!-- Autre utilisateur connecté -->
                        <?php if(!empty($mesObjets)): ?>
                            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#proposeModal">
                                <i class='bx bx-refresh'></i> Proposer un échange
                            </button>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <p class="mb-0">Pour proposer un échange, vous devez d'abord ajouter vos propres objets.</p>
                                <a href="/objets/nouveau" class="btn btn-sm btn-info mt-2">Ajouter un objet</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p class="mb-0">Connectez-vous pour proposer un échange pour cet objet.</p>
                        <a href="/login" class="btn btn-sm btn-warning mt-2">Se connecter</a>
                    </div>
                <?php endif; ?>
                <a href="/objets" class="btn btn-outline-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour proposer un échange -->
<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $objet['user_id'] && !empty($mesObjets)): ?>
<div class="modal fade" id="proposeModal" tabindex="-1" aria-labelledby="proposeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proposeModalLabel">Proposer un échange</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/echanges/proposer" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="objet_demande_id" value="<?php echo $objet['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Je propose cet objet :</label>
                        <select class="form-select" name="objet_propose_id" required>
                            <option value="">Choisir un objet</option>
                            <?php foreach($mesObjets as $monObjet): ?>
                                <option value="<?php echo $monObjet['id']; ?>">
                                    <?php echo htmlspecialchars($monObjet['nom']); ?> (<?php echo htmlspecialchars($monObjet['categorie']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message (optionnel) :</label>
                        <textarea class="form-control" name="message" rows="3" placeholder="Expliquez pourquoi vous voulez échanger..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Proposer l'échange</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-ouvrir le modal si paramètre dans l'URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('propose') === 'true') {
            var modal = new bootstrap.Modal(document.getElementById('proposeModal'));
            modal.show();
        }
    });
</script>
<?php endif; ?>

<?php include '../app/views/layout/footer.php'; ?>