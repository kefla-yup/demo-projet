<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- Start Banner Hero -->
<div class="banner-wrapper bg-light">
    <div id="index_banner" class="banner-vertical-center-index container-fluid pt-5">
        <div class="py-5 row d-flex align-items-center">
            <div class="banner-content col-lg-8 col-8 offset-2 m-lg-auto text-left py-5 pb-5">
                <h1 class="banner-heading h1 text-secondary display-3 mb-0 pb-5 mx-0 px-0 light-300 typo-space-line">
                    Échangez vos objets <br>et découvrez des trésors
                </h1>
                <p class="banner-body text-muted py-3 mx-0 px-0">
                    Takalo-takalo est une plateforme d'échange d'objets entre particuliers. 
                    Donnez une seconde vie à vos objets et trouvez ce dont vous avez besoin 
                    sans dépenser d'argent.
                </p>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a class="banner-button btn rounded-pill btn-outline-primary btn-lg px-4" href="/register" role="button">Commencer maintenant</a>
                <?php else: ?>
                    <a class="banner-button btn rounded-pill btn-outline-primary btn-lg px-4" href="/objets" role="button">Voir les objets</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- End Banner Hero -->

<!-- Start Recent Objects -->
<section class="container py-5">
    <div class="recent-work-header row text-center pb-5">
        <h2 class="col-md-6 m-auto h2 semi-bold-600 py-5">Objets récemment ajoutés</h2>
    </div>
    
    <div class="row gy-5 g-lg-5 mb-4">
        <?php if(empty($objets)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Aucun objet disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <?php foreach($objets as $objet): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-lg overflow-hidden h-100">
                    <?php if($objet['photo']): ?>
                        <img class="card-img-top img-objet" src="/uploads/<?php echo htmlspecialchars($objet['photo']); ?>" alt="<?php echo htmlspecialchars($objet['nom']); ?>">
                    <?php else: ?>
                        <img class="card-img-top img-objet" src="/assets/img/no-image.jpg" alt="Pas d'image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($objet['nom']); ?></h5>
                        <p class="card-text text-muted"><?php echo substr(htmlspecialchars($objet['description']), 0, 100) . '...'; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><?php echo htmlspecialchars($objet['categorie']); ?></small>
                            <a href="/objets/<?php echo $objet['id']; ?>" class="btn btn-outline-primary btn-sm">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php if(!empty($objets)): ?>
        <div class="text-center">
            <a href="/objets" class="btn btn-primary px-5">Voir tous les objets</a>
        </div>
    <?php endif; ?>
</section>
<!-- End Recent Objects -->

<?php include __DIR__ . '/../layout/footer.php'; ?>