<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5 text-center">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="display-1 text-muted">404</h1>
            <h2 class="h3 mb-4">Page non trouvée</h2>
            <p class="lead mb-4">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="/" class="btn btn-outline-primary btn-lg">Retour à l'accueil</a>
                <?php if (isset(
                    
                    $_SESSION['user_id'])): ?>
                    <a href="/objets/nouveau" class="btn btn-primary btn-lg">Ajouter un objet</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary btn-lg">Se connecter pour ajouter</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>