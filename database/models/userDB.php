<?php

require_once($dir . '/database/pdoOpen.php');

class UserDB
{
    private PDOStatement $statementInsertUser;
    private PDOStatement $statementSelectUser;

    function __construct(private $pdo)
    {
        $this->statementInsertUser = $pdo->prepare("INSERT INTO user (idUser, name, email, password) VALUES (DEFAULT, :name, :email, :password)");
        $this->statementSelectUser = $pdo->prepare("SELECT idUser, name, email, password FROM user WHERE email=:email");
    }

    function insertUser($name, $email, $password)
    {
        $this->statementInsertUser->bindValue(':name', $name);
        $this->statementInsertUser->bindValue(':email', $email);
        $this->statementInsertUser->bindValue(':password', $password);

        $this->statementInsertUser->execute();
    }

    function selectUser($email)
    {

        $this->statementSelectUser->bindValue(':email', $email);

        $this->statementSelectUser->execute();

        return $this->statementSelectUser->fetch();
    }
}

return new UserDB($pdo);
