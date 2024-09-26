<?php

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
    <header>LilyBlog - Accueil</header>
    <main>

        <article class=domains>
            <ul>
                <?php
                foreach ($domainsTable as $domainSingle) :
                    // echo '<pre>';
                    // print_r($domainSingle);
                    // echo '</pre>';
                    if ($domainSingle['number'] != 0):
                ?>
                        <li>
                            <a href=<?= "'./index.php?domain=" . $domainSingle['idDomain'] . "'" ?> style="text-decoration: none; color:black">
                                <?= $domainSingle['name'] . ' (' . $domainSingle['number'] . ')' ?>
                            </a>
                        </li>

                <?php endif;
                endforeach; ?>

                <li>
                    <a href='./index.php' style="text-decoration: none; color:black">
                        <?= 'Toutes consoles confondues (' . $allNumber . ')' ?>
                    </a>
                </li>
            </ul>
        </article>
        <ul class=gallery>
            <?php if (!empty($articleTable)) :

                foreach ($articleTable as $article) : ?>


                    <li>
                        <a href=<?= './lecture.php?id=' . $article['id'] ?> style="text-decoration: none">
                            <div class=img-container style="background-image:url(<?= $article['picture'] ?>)"></div>
                            <p class=domainArticle style="background-color:<?= $article['color'] ?>;"><?= $article['name'] ?></p>
                            <p><?= $article['title'] ?></p>
                        </a>
                    </li>

                <?php endforeach;
            else: ?>
                <p>Aucun article à présenter pour le moment. Veuillez en créer un, s'il vous plaît.</p>
            <?php endif; ?>


        </ul>
        <div class=create>
            <a href="./edit.php?action=create" style="text-decoration: none" ;>
                <button>+</button>
            </a>
        </div>

    </main>

    <footer>Lily Creative Commons</footer>
</body>

</html>