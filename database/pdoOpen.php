<?php

$dns = "mysql:host=localhost;dbname=blog";
$user = "Lily";
$pwd = "SQLPAM230623";

$pdo = new PDO($dns, $user, $pwd, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
