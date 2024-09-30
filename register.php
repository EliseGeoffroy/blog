<?php
$dir = __DIR__;
$userDB = require_once($dir . '/database/models/userDB.php');

const ERROR_ABSENT = 'Veuillez renseigner ce champ.';
const ERROR_INVALID = 'Ce champ n\'est pas valide. Veuillez le corriger.';

$username = '';
$email = '';

$error = [
    'email' => '',
    'username' => '',
    'password' => ''
];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $inputPOST = filter_input_array(INPUT_POST, [
        'email' => [
            'filter' => FILTER_SANITIZE_EMAIL,
            'filter' => FILTER_VALIDATE_EMAIL
        ],
        'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    if ($_POST['email'] == '') {
        $error['email'] = ERROR_ABSENT;
    } else if ($inputPOST['email'] == false) {
        $error['email'] = ERROR_INVALID;
    } else {
        $email = $inputPOST['email'];
    }



    if ($inputPOST['username'] == '') {
        $error['username'] = ERROR_ABSENT;
    } else {
        $username = $inputPOST['username'];
    }

    if ($_POST['password'] == '') {
        $error['password'] = ERROR_ABSENT;
    } else {
        $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
    }

    if (count(array_filter($error, fn($el) => $el != '')) == 0) {
        $userDB->insertUser($username, $email, $password);
        header('Location:./index.php');
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
    <header>
        LilyBlog - Inscription
        <?php require_once($dir . '/includes/nav.php') ?>
    </header>
    <?php require_once $dir . '/includes/home.php' ?>
    <main>

        <form action='./register.php' method='POST'>
            <h1> Veuillez renseigner les informations suivantes </h1>
            <div class="username item">
                <input type=text placeholder="Nom d'utilisateur" id=username name=username value=<?= $username ?>>
                <?= ($error['username'] != '') ? '<p class=error>' . $error['username'] . '</p>' : '' ?>

            </div>
            <div class="email item">

                <input type=text placeholder="Email" id=email name=email value=<?= $email ?>>
                <?= ($error['email'] != '') ? '<p class=error>' . $error['email'] . '</p>' : '' ?>
            </div>
            <div class="password item">

                <input type=text placeholder="Mot de passe" id=password name=password>
                <?= ($error['password'] != '') ? '<p class=error>' . $error['password'] . '</p>' : '' ?>
            </div>
            <button class=accountAction type submit>Créer mon compte</button>
        </form>
    </main>

</body>

</html>