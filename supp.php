<?php


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

require_once('./database/pdoOpen.php');
$article = require_once('./database/models/ArticleDB.php');

$article->deleteArticle($_GET['id']);

header('Location:./index.php');
