<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $title ?? 'Takalo-takalo'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="/assets/css/boxicon.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body>
    <!-- Navigation -->
    <nav id="main_nav" class="navbar navbar-expand-lg navbar-light bg-white shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand h1" href="/">
                <i class='bx bx-recycle bx-sm text-dark'></i>
                <span class="text-dark h4">Takalo</span> <span class="text-primary h4">-takalo</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggler-success" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="navbar-toggler-success">
                <div class="flex-fill mx-xl-5 mb-2">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-xl-5 text-center text-dark">
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="/objets">Objets</a>
                        </li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <a class="nav-link btn-outline-primary rounded-pill px-3" href="/dashboard">Tableau de bord</a>
                            </li>
                            <?php if(!empty($_SESSION['user_is_admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link btn-outline-primary rounded-pill px-3" href="/admin">Admin</a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link btn-outline-primary rounded-pill px-3" href="/objets/nouveau">Ajouter un objet</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-outline-primary rounded-pill px-3" href="/echanges">Mes échanges</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a class="nav-link" href="/profile">
                            <i class='bx bx-user-circle bx-sm text-primary'></i>
                            <?php echo htmlspecialchars($_SESSION['user_nom']); ?>
                        </a>
                        <a class="nav-link" href="/logout">
                            <i class='bx bx-log-out bx-sm text-primary'></i>
                        </a>
                    <?php else: ?>
                        <a class="nav-link btn-outline-primary rounded-pill px-3" href="/login">Connexion</a>
                        <a class="nav-link btn-outline-primary rounded-pill px-3" href="/register">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navigation -->

    <!-- Messages Flash -->
    <?php if(function_exists('Flight') && method_exists('Flight', 'displayFlash')): ?>
        <div class="container mt-3">
            <?php echo Flight::displayFlash(); ?>
        </div>
    <?php endif; ?>