<header>LilyBlog - Accueil
    <?php require_once $dir . '/includes/authentication.php' ?>

    <?php if (is_array(isLogged($sessionDB, $secretKey))): ?>

        <nav>

            <a class="navtab" href='./profile.php'>
                <?= isLogged($sessionDB, $secretKey)['name'] ?>
            </a>

            <a class="navtab" href='./logout.php'>
                Déconnexion
            </a>
        </nav>

    <?php else: ?>
        <nav>
            <a class="navtab" href='./register.php'>
                Inscription
            </a>
            <a class="navtab" href='./login.php'>
                Connexion
            </a>
        </nav>
    <?php endif; ?>



</header>