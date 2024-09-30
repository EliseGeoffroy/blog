<?php
$dir = __DIR__;
$sessionDB = require_once $dir . '/database/models/sessionDB.php';

$idSession = $_COOKIE['session'];
$sessionDB->deleteSession($idSession);

setcookie('session', '', time() - 1);
setcookie('signature', '', time() - 1);

header('Location:./index.php');
