<?php require_once $dir . '/includes/authentication.php';
$login = isLogged($sessionDB, $secretKey)
if (is_array($login)) {
    $name =$login['name'];
    $isLogged = true;
} else {
    $isLogged = false;
    $name = '';
}
?>





<?php if ($isLogged): ?>

    <nav>

        <a class="navtab" href='./profile.php?username=<?= $name ?>'>
            <?= $name ?>
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