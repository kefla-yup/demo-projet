<?php include '../app/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Créer un compte</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                                <p class="mb-0"><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($data['nom']) ? htmlspecialchars($data['nom']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimum 6 caractères</small>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone (optionnel)</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo isset($data['telephone']) ? htmlspecialchars($data['telephone']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse (optionnel)</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="2"><?php echo isset($data['adresse']) ? htmlspecialchars($data['adresse']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Déjà un compte ? <a href="/login">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>