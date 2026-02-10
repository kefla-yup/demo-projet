<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/objets">Objets</a></li>
            <li class="breadcrumb-item"><a href="/objets/<?php echo $objetDemande['id']; ?>"><?php echo htmlspecialchars($objetDemande['nom']); ?></a></li>
            <li class="breadcrumb-item active">Proposer un échange</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Proposer un échange</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Objet demandé -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Objet que vous voulez</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if($objetDemande['photo']): ?>
                                        <img src="/uploads/<?php echo htmlspecialchars($objetDemande['photo']); ?>" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                    <?php else: ?>
                                        <img src="/assets/img/no-image.jpg" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                    <?php endif; ?>
                                    <h5><?php echo htmlspecialchars($objetDemande['nom']); ?></h5>
                                    <p class="text-muted small">Propriétaire : <?php echo htmlspecialchars($objetDemande['proprietaire']); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Objet à proposer -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Objet que vous proposez</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="/echanges/proposer">
                                        <input type="hidden" name="objet_demande_id" value="<?php echo $objetDemande['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Choisissez votre objet :</label>
                                            <select class="form-select" name="objet_propose_id" required id="objetSelect">
                                                <option value="">Sélectionner un objet</option>
                                                <?php foreach($mesObjets as $objet): ?>
                                                    <option value="<?php echo $objet['id']; ?>" data-photo="<?php echo $objet['photo'] ? '/uploads/' . htmlspecialchars($objet['photo']) : '/assets/img/no-image.jpg'; ?>">
                                                        <?php echo htmlspecialchars($objet['nom']); ?> (<?php echo htmlspecialchars($objet['categorie']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <!-- Aperçu de l'objet sélectionné -->
                                        <div id="objetPreview" class="text-center d-none">
                                            <img id="previewImage" src="" class="img-fluid rounded mb-2" style="max-height: 150px;">
                                            <h6 id="previewNom" class="mb-0"></h6>
                                            <p id="previewCategorie" class="text-muted small"></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Message au propriétaire :</label>
                                            <textarea class="form-control" name="message" rows="3" placeholder="Expliquez pourquoi vous souhaitez échanger cet objet..."></textarea>
                                            <small class="text-muted">Optionnel, mais recommandé pour augmenter vos chances</small>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class='bx bx-info-circle'></i>
                                            <small>
                                                En proposant cet échange, vous acceptez de donner votre objet 
                                                si le propriétaire accepte votre proposition.
                                            </small>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Proposer l'échange</button>
                                            <a href="/objets/<?php echo $objetDemande['id']; ?>" class="btn btn-outline-secondary">Annuler</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const objetSelect = document.getElementById('objetSelect');
        const objetPreview = document.getElementById('objetPreview');
        const previewImage = document.getElementById('previewImage');
        const previewNom = document.getElementById('previewNom');
        const previewCategorie = document.getElementById('previewCategorie');
        
        objetSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const photo = selectedOption.getAttribute('data-photo');
                const nom = selectedOption.text.split(' (')[0];
                const categorie = selectedOption.text.match(/\((.*)\)/)[1];
                
                previewImage.src = photo;
                previewNom.textContent = nom;
                previewCategorie.textContent = categorie;
                objetPreview.classList.remove('d-none');
            } else {
                objetPreview.classList.add('d-none');
            }
        });
    });
</script>

<?php include '../app/views/layout/footer.php'; ?>