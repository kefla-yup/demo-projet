<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <h1 class="h3 mb-4">Tableau de bord Admin</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs inscrits</h5>
                    <p class="display-6"><?php echo intval($usersCount); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Échanges</h5>
                    <p class="display-6"><?php echo intval($echangesCount); ?></p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
