<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h2 text-center mb-5">Objets disponibles</h1>
    
    <!-- Filtres par catégorie -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form class="row g-2 align-items-center" method="GET" action="/objets">
                <div class="col-auto">
                    <input type="text" class="form-control" name="q" placeholder="Mot-clé" value="<?php echo htmlspecialchars($q ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <select class="form-select" name="categorie_id">
                        <option value="">Tous</option>
                        <?php foreach($categories as $categorie): ?>
                            <option value="<?php echo $categorie['id']; ?>" <?php echo ($selectedCategorie == $categorie['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($categorie['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Rechercher</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des objets -->
    <div class="row">
        <?php if(empty($objets)): ?>
            <div class="col-12 text-center py-5">
                <i class='bx bx-package display-1 text-muted'></i>
                <h3 class="h4 text-muted mt-3">Aucun objet disponible dans cette catégorie</h3>
                <a href="/objets" class="btn btn-primary mt-3">Voir tous les objets</a>
            </div>
        <?php else: ?>
            <?php foreach($objets as $objet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm objet-card">
                        <?php if($objet['photo']): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($objet['photo']); ?>" class="card-img-top img-objet" alt="<?php echo htmlspecialchars($objet['nom']); ?>">
                        <?php else: ?>
                            <img src="/assets/img/no-image.jpg" class="card-img-top img-objet" alt="Pas d'image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($objet['nom']); ?></h5>
                            <?php if(!empty($objet['prix_estime'])): ?>
                                <p class="mb-2"><strong>Prix estimé : </strong><?php echo number_format($objet['prix_estime'], 2, ',', ' '); ?> €</p>
                            <?php endif; ?>
                            <p class="card-text text-muted"><?php echo substr(htmlspecialchars($objet['description']), 0, 150) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($objet['categorie']); ?></span>
                                <span class="text-muted small">Par <?php echo htmlspecialchars($objet['proprietaire']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="/objets/<?php echo $objet['id']; ?>" class="btn btn-primary w-100">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>