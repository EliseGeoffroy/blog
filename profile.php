<?php
$dir = __DIR__;



$articleDB = require_once $dir . '/database/models/ArticleDB.php';
$domainDB = require_once $dir . '/database/models/DomainDB.php';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$username = $_GET['username'];

require_once $dir . '/includes/authentication.php';
if (isLogged($sessionDB, $secretKey)['name'] != $_GET['username']) {
    header('Location:/');
}

if (isset($_GET['domain'])) {
    $articleTable = $articleDB->selectByAuthorAndDomain($_GET['domain'], $username);
} else {
    $articleTable = $articleDB->selectByAuthor($username);
}

$domainsTable = [];
$domainsTable = $domainDB->selectByAuthor($username);

$allNumber = array_reduce(array_column($domainsTable, 'number'), fn($a, $b) => $a + $b);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel='stylesheet' href='./public/css/profile-style.css'>
</head>

<body>
    <header>LilyBlog - Vos articles
        <?php require_once($dir . '/includes/nav.php') ?>
    </header>
    <?php require_once $dir . '/includes/home.php' ?>
    <h1>Les articles de <?= $username ?></h1>
    <main>



        <article class=domains>
            <ul>
                <?php
                foreach ($domainsTable as $domainSingle) :
                    if ($domainSingle['number'] != 0):
                ?>
                        <li>
                            <a href=<?= "'./profile.php?username=" . $username . "&domain=" . $domainSingle['idDomain'] . "'" ?>>
                                <?= $domainSingle['name'] . ' (' . $domainSingle['number'] . ')' ?>
                            </a>
                        </li>

                <?php endif;
                endforeach; ?>

                <li>
                    <a href='./index.php'>
                        <?= 'Toutes consoles confondues (' . $allNumber . ')' ?>
                    </a>
                </li>
            </ul>
        </article>
        <ul class=gallery>
            <?php if (!empty($articleTable)) :

                foreach ($articleTable as $article) : ?>


                    <li>
                        <a href=<?= './lecture.php?id=' . $article['id'] ?>>
                            <div class=img-container style="background-image:url(<?= $article['picture'] ?>)"></div>
                            <p class=domainArticle style="background-color:<?= $article['color'] ?>;"><?= $article['name'] ?></p>
                            <p><?= $article['title'] ?></p>
                            <p>Par <?= $article['author'] ?></p>
                        </a>
                    </li>

                <?php endforeach;
            else: ?>
                <p>Aucun article à présenter pour le moment. Veuillez en créer un, s'il vous plaît.</p>
            <?php endif; ?>


        </ul>
        <?php if ($isLogged): ?>
            <div class=create>
                <a href="./edit.php?action=create">
                    <button>+</button>
                </a>
            </div>
        <?php endif; ?>

    </main>

</html>