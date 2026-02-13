<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h3">Nouvelle catégorie</h1>
    <form action="/admin/categories/nouveau" method="POST">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <button class="btn btn-primary">Créer</button>
        <a href="/admin/categories" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
