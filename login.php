<?php
$dir = __DIR__;
$userDB = require_once $dir . '/database/models/userDB.php';
$sessionDB = require_once $dir . '/database/models/sessionDB.php';
require_once $dir . '/data/secretkey.php';

$email = '';
$password = '';

$error = [
    'email' => '',
    'password' => ''
];


const ERROR_ABSENT = 'Veuillez renseigner ce champ.';
const ERROR_UNKNOWN = " Cet identifiant n'existe pas. Veuillez vous inscrire.";
const ERROR_WRONG = " Ce mot de passe n'est pas le bon. Veuillez réessayer.";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputPOST = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL
    ]);

    if ($inputPOST['email'] == '') {
        $error['email'] = ERROR_ABSENT;
    } else {
        $email = $inputPOST['email'];
    }
    if ($_POST['password'] == '') {
        $error['password'] = ERROR_ABSENT;
    }


    if (count(array_filter($error, fn($e) => $e != '')) == 0) {

        $userArray = $userDB->selectUser($inputPOST['email']);

        if (empty($userArray)) {
            $error['email'] = ERROR_UNKNOWN;
        } else {

            if (password_verify($_POST['password'], $userArray['password'])) {

                //création de la session
                $idSession = bin2hex(random_bytes(32));
                $sessionDB->insertSession($idSession, $userArray['idUser']);



                //création des cookies
                setcookie('session', $idSession, time() + 60 * 60 * 24, '', '', false, true);
                $signature = hash_hmac('sha256', $idSession, $secretKey);
                setcookie('signature', $signature, time() + 60 * 60 * 24, '', '', false, true);

                header('Location:./index.php');
            } else {
                $error['password'] = ERROR_WRONG;
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='stylesheet' href="./public/css/authentication-style.css">
</head>

<body>
    <header> LilyBlog - Connexion
        <?php require_once($dir . '/includes/nav.php') ?>
    </header>
    <main>

        <form action='./login.php' method='POST'>
            <h1>Connexion</h1>

            <div class="email item">

                <input type=text placeholder="Email" id=email name=email value=<?= $email ?>>
                <?= ($error['email'] != '') ? '<p class=error>' . $error['email'] . '</p>' : '' ?>
            </div>
            <div class="password item">

                <input type=text placeholder="Mot de passe" id=password name=password>
                <?= ($error['password'] != '') ? '<p class=error>' . $error['password'] . '</p>' : '' ?>
            </div>
            <button class=accountAction type submit>Se connecter</button>
        </form>
    </main>

</body>

</html>