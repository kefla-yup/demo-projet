    <!-- Footer -->
    <footer class="bg-secondary pt-4 mt-5">
        <div class="container">
            <div class="row py-4">
                <div class="col-lg-4 col-12 align-left">
                    <a class="navbar-brand" href="/">
                        <i class='bx bx-recycle bx-sm text-light'></i>
                        <span class="text-light h5">Takalo</span> <span class="text-light h5 semi-bold-600">-takalo</span>
                    </a>
                    <p class="text-light my-lg-4 my-2">
                        Plateforme d'échange d'objets entre particuliers. 
                        Donnez une seconde vie à vos objets !
                    </p>
                    <ul class="list-inline footer-icons light-300">
                        <li class="list-inline-item m-0">
                            <a class="text-light" href="#" target="_blank">
                                <i class='bx bxl-facebook-square bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item m-0">
                            <a class="text-light" href="#" target="_blank">
                                <i class='bx bxl-twitter-square bx-md'></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-0 mt-4">
                    <h3 class="h4 pb-lg-3 text-light light-300">Navigation</h3>
                    <ul class="list-unstyled text-light light-300">
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light" href="/">Accueil</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light py-1" href="/objets">Objets disponibles</a>
                        </li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light py-1" href="/dashboard">Tableau de bord</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light py-1" href="/profile">Mon profil</a>
                            </li>
                        <?php else: ?>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light py-1" href="/login">Connexion</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a class="text-decoration-none text-light py-1" href="/register">Inscription</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 my-sm-0 mt-4">
                    <h3 class="h4 pb-lg-3 text-light light-300">Contact</h3>
                    <ul class="list-unstyled text-light light-300">
                        <li class="pb-2">
                            <i class='bx-fw bx bx-envelope bx-xs'></i>
                            <span>contact@takalo-takalo.mg</span>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bx-phone bx-xs'></i>
                            <span>+261 34 00 000 00</span>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bx-map bx-xs'></i>
                            <span>Antananarivo, Madagascar</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="w-100 bg-primary py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-lg-12 col-sm-12">
                        <p class="text-lg-center text-center text-light light-300">
                            © <?php echo date('Y'); ?> Takalo-takalo. Tous droits réservés.
                        </p>
                        <p class="text-lg-center text-center text-light light-300">
                            ETU003877 Itiela <br>
                            ETU004179 Houssena <br>
                            ETU003888 Armella
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <!-- Bootstrap JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="/assets/js/jquery.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/custom.js"></script>
    
    <?php if(isset($scripts)): ?>
        <?php echo $scripts; ?>
    <?php endif; ?>
</body>
</html>