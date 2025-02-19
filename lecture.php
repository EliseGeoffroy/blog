<?php

$dir = __DIR__;


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require_once($dir . '/database/pdoOpen.php');
$articleDB = require_once($dir . '/database/models/ArticleDB.php');


$article = $articleDB->selectById($_GET['id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel='stylesheet' href="./public/css/lecture-style.css">

</head>

<body>
    <header>LilyBlog - Lecture
        <?php require_once $dir . '/includes/nav.php' ?>
    </header>
    <?php require_once $dir . '/includes/home.php' ?>

    <main>
        <article class=news>
            <img src=<?= $article['picture'] ?>>
            <h1><?= $article['title'] ?></h1>
            <p class="author">Par <?= $article['author'] ?></p>
            <p class="contains"><?= $article['contain'] ?></p>
        </article>
        <article class=actions>
            <?php if ($name == $article['author']): ?>
                <a href='./supp.php?id=<?= $article['id'] ?>'>
                    <button class=supp name='Supp'>Supprimer l'article</button>
                </a>
                <a href=<?= './edit.php?action=modify&id=' . $article['id'] ?>>
                    <button class=edit name='Edit'>Editer l'article</button>
                </a>
            <?php endif; ?>
        </article>
    </main>
    <footer>Lily Creative Commons</footer>
</body>

</html>