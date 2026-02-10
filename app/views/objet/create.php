<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Ajouter un nouvel objet</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="/objets/nouveau" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'objet *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label">Catégorie *</label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">Choisir une catégorie</option>
                                <?php foreach($categories as $categorie): ?>
                                    <option value="<?php echo $categorie['id']; ?>">
                                        <?php echo htmlspecialchars($categorie['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            <small class="text-muted">Décrivez votre objet en détail (état, marque, dimensions, etc.)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de l'objet</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <small class="text-muted">Formats acceptés: JPG, PNG, GIF (max 5MB)</small>
                            <div class="mt-2" id="imagePreview"></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Ajouter l'objet</button>
                            <a href="/dashboard" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Aperçu de l'image
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
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>