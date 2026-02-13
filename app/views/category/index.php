<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h3">Gestion des catégories</h1>
    <a href="/admin/categories/nouveau" class="btn btn-primary mb-3">Nouvelle catégorie</a>

    <?php if(!empty($categories)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['nom']); ?></td>
                    <td>
                        <a href="/admin/categories/<?php echo $c['id']; ?>/editer" class="btn btn-sm btn-warning">Éditer</a>
                        <form action="/admin/categories/<?php echo $c['id']; ?>/supprimer" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cette catégorie ?');">
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aucune catégorie trouvée.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
