<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h3">Modifier catégorie</h1>
    <form action="/admin/categories/<?php echo $categorie['id']; ?>/editer" method="POST">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($categorie['nom']); ?>" required>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="/admin/categories" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
