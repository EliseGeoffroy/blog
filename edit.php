<?php
$dir = __DIR__;

require_once $dir . '/includes/authentication.php';
if (!isLogged($sessionDB, $secretKey)) {
    header('Location:/');
}


$articleDB = require_once('./database/models/ArticleDB.php');
$domainDB = require_once('./database/models/DomainDB.php');

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$create = ($_GET['action'] == 'create') | ($_GET['action'] == 'createSave') ? true : false;
$article = [
    'id' => null,
    'title' => null,
    'contain' => null,
    'picture' => null,
    'domain' => null,
    'name' => null

];
if ($_GET['action'] == 'modify') {
    $article = $articleDB->selectById($_GET['id']);
}

$domainsTable = $domainDB->selectAll();

$errorTable = [
    'title' => '',
    'picture' => '',
    'domain' => '',
    'newDomain' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('./includes/save.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="./public/css/edit-style.css">
</head>

<body>
    <header>LilyBlog - Edition
        <?php require_once $dir . '/includes/nav.php' ?>
    </header>

    <main>
        <?php require_once $dir . '/includes/home.php' ?>

        <?= $create ? '<form action="./edit.php?action=createSave" method="POST">' : '<form action="./edit.php?action=modifySave&id=' . $_GET['id'] . '" method="POST">' ?>
        <div class=formulaire>
            <div class=title>
                <label for="title">Titre de l'article</label>
                <input type="text" name="title" id="title" value="<?= $article['title'] ?? '' ?>">
            </div>
            <?= ($errorTable['title'] != '') ? '<p style="color:red">' . $errorTable['title'] . '</p>' : '' ?>
            <div class=contain>
                <label for="contain">Contenu de l'article</label>
                <textarea name="contain" id="contain"><?= $article['contain'] ?? '' ?></textarea>
            </div>

            <div class=picture>
                <label for="picture">Adresse URL vers l'illustration</label>
                <input type="url" name="picture" id="picture" value="<?= $article['picture'] ?? '' ?>">
            </div>
            <?= ($errorTable['picture'] != '') ? '<p style="color:red">' . $errorTable['picture'] . '</p>' : '' ?>
            <div class=domain>
                <label for=" domain">Catégorie de l'article</label>
                <select name="domain" id="domain" value="<?= $article['domain'] ?? '' ?>">
                    <?php foreach ($domainsTable as $domain): ?>

                        <option value=<?= $domain['idDomain'] ?> <?= ($article['name'] == $domain['name']) ? 'selected' : '' ?>><?= $domain['name'] ?></option>
                    <?php endforeach; ?>
                    <option value="autre" <?= ($article['name'] == 'autre') ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>
            <?= ($errorTable['domain'] != '') ? '<p style="color:red">' . $errorTable['domain'] . '</p>' : '' ?>
            <div class=newDomain>
                <label for='newDomain'>Si autre, précisez.</label>
                <input type='text' name="newDomain" id="newDomain" value="<?= $article['newDomain'] ?? '' ?>">
            </div>
            <?= ($errorTable['newDomain'] != '') ? '<p style="color:red">' . $errorTable['newDomain'] . '</p>' : '' ?>
        </div>
        <div class=actions>
            <?= $create ? '<button type="submit" class=save >Sauvegarder l\'article</button>' : '<button type="submit" class=save >Modifier l\'article</button>'; ?>
        </div>
        </form>


    </main>
    <footer> Lily Creative Commons</footer>

</body>

</html>