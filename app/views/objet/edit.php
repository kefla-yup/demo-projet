<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/dashboard">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="/objets/<?php echo $objet['id']; ?>"><?php echo htmlspecialchars($objet['nom']); ?></a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">Modifier l'objet</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="/objets/<?php echo $objet['id']; ?>/editer" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'objet *</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($objet['nom']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">Choisir une catégorie</option>
                                <?php foreach($categories as $categorie): ?>
                                    <option value="<?php echo $categorie['id']; ?>" <?php echo $objet['categorie_id'] == $categorie['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categorie['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($objet['description']); ?></textarea>
                        </div>
                        
                        <!-- Photo actuelle -->
                        <div class="mb-3">
                            <label class="form-label">Photo actuelle</label>
                            <div>
                                <?php if($objet['photo']): ?>
                                    <img src="/uploads/<?php echo htmlspecialchars($objet['photo']); ?>" class="img-thumbnail mb-2" style="max-height: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supprimer_photo" id="supprimer_photo" value="1">
                                        <label class="form-check-label" for="supprimer_photo">
                                            Supprimer cette photo
                                        </label>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Aucune photo</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Nouvelle photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Nouvelle photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <small class="text-muted">Laisser vide pour conserver la photo actuelle</small>
                            <div class="mt-2" id="imagePreview"></div>
                        </div>
                        
                        <!-- Disponibilité -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="est_disponible" id="est_disponible" value="1" <?php echo $objet['est_disponible'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="est_disponible">
                                    Cet objet est disponible pour échange
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">Mettre à jour</button>
                            <a href="/objets/<?php echo $objet['id']; ?>" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Aperçu de la nouvelle image
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail mt-2" style="max-height: 200px;">';
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });
    
    // Gestion de la suppression de photo
    document.getElementById('supprimer_photo')?.addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('photo').disabled = true;
        } else {
            document.getElementById('photo').disabled = false;
        }
    });
</script>

<?php include '../app/views/layout/footer.php'; ?>