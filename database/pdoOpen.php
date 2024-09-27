<?php

$pdoSettings = json_decode(file_get_contents(__DIR__ . '/pdoSettings.json'), true);

$dns = $pdoSettings['dns'];
$user = $pdoSettings['user'];
$pwd = $pdoSettings['pwd'];


try {
    $pdo = new PDO($dns, $user, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {

    echo 'Connexion échouée' . $e->getMessage();
}
