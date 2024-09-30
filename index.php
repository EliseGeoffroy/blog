<?php
$dir = __DIR__;
$articleDB = require_once('./database/models/ArticleDB.php');
$domainDB = require_once('./database/models/DomainDB.php');



if (isset($_GET['domain'])) {
    $articleTable = $articleDB->selectByDomain($_GET['domain']);
} else {
    $articleTable = $articleDB->selectAll();
}

$domainsTable = $domainDB->selectAll();

$allNumber = 0;

foreach ($domainsTable as &$domain) {
    $result = $articleDB->selectByDomain($domain['idDomain']);
    $number = count($result);
    $domain = [...$domain, 'number' => $number];

    $allNumber += $number;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel='stylesheet' href="./public/css/index-style.css">

</head>

<body>
    <header>LilyBlog - Accueil
        <?php require_once($dir . '/includes/nav.php') ?>
    </header>
    <main>

        <article class=domains>
            <ul>
                <?php
                foreach ($domainsTable as $domainSingle) :
                    if ($domainSingle['number'] != 0):
                ?>
                        <li>
                            <a href=<?= "'./index.php?domain=" . $domainSingle['idDomain'] . "'" ?>>
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

    <footer>Lily Creative Commons</footer>
</body>

</html>