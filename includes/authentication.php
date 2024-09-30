<?php

require_once $dir . '/data/secretkey.php';
$sessionDB = require_once $dir . '/database/models/sessionDB.php';

function isLogged($sessionDB, $secretKey)
{
    if ((isset($_COOKIE['session'])) && isset($_COOKIE['signature'])) {
        $idSession = $_COOKIE['session'];
        $signature = $_COOKIE['signature'];
        $hash = hash_hmac('sha256', $idSession, $secretKey);

        if (hash_equals($hash, $signature)) {
            $user = $sessionDB->selectJoin($idSession);
            return $user;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
