<?php

if (!isset($sessionDB)) {
    $sessionDB = require_once $dir . '/database/models/sessionDB.php';
}

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$_POST = filter_input_array(INPUT_POST, [
    'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'contain' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'domain' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'picture' => FILTER_SANITIZE_URL,
    'newDomain' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
]);

//Vérification des données
$errorTable = [
    'title' => '',
    'picture' => '',
    'domain' => '',
    'newDomain' => ''
];


const ERROR_ABSENT = 'Veuillez renseigner ce champ';
const ERROR_URL = "L'url proposée n'est pas valide";

$errorTable['title'] = ($_POST['title'] == '') ? ERROR_ABSENT : '';
$errorTable['picture'] = ($_POST['picture'] == '') ? ERROR_ABSENT : '';
$errorTable['domain'] = ($_POST['domain'] == '') ? ERROR_ABSENT : '';
$errorTable['newDomain'] = (($_POST['domain'] == 'autre') && ($_POST['newDomain'] == '')) ? ERROR_ABSENT : '';



if (count(array_filter($errorTable, fn($el) => $el != '')) == 0) {

    $new = $_GET['action'] == 'createSave' ? true : false;

    if ($_POST['domain'] == 'autre') {

        $color = 'rgb(' . mt_rand(50, 200) . ',' . mt_rand(50, 200) . ',' . mt_rand(50, 200) . ')';
        $domainArticle = $domainDB->insertDomain($_POST['newDomain'], $color);
    } else {
        $domainArticle = $_POST['domain'];
    }

    //Author searching
    $author = $sessionDB->selectJoin($_COOKIE['session'])['name'];


    if ($new) {
        $articleDB->insertArticle($_POST['title'], $_POST['contain'], $_POST['picture'], $author, $domainArticle);
    } else {

        $articleDB->updateArticle($_POST['title'], $_POST['contain'], $_POST['picture'], $domainArticle, $_GET['id']);
    }

    header('Location:./index.php');
} else {

    $article = [
        'id' => null,
        'title' => $_POST['title'],
        'contain' => $_POST['contain'],
        'picture' => $_POST['picture'],
        'domain' => $_POST['domain'],
        'newDomain' => $_POST['newDomain']
    ];
}
