<?php

require_once $dir . '/database/pdoOPen.php';

class SessionDB
{
    private PDOStatement $statementInsertSession;
    private PDOStatement $statementDeleteSession;

    function __construct(private $pdo)
    {
        $this->statementInsertSession = $pdo->prepare("INSERT INTO session(idSession,idUser) VALUES (:idSession,:idUser)");
        $this->statementDeleteSession = $pdo->prepare("DELETE FROM session WHERE idSession=:idSession");
    }

    function insertSession($idSession, $idUser)
    {
        $this->statementInsertSession->bindValue(':idSession', $idSession);
        $this->statementInsertSession->bindValue(':idUser', $idUser);

        $this->statementInsertSession->execute();
    }

    function deleteSession($idSession)
    {
        $this->statementDeleteSession->bindValue(':idSession', $idSession);

        $this->statementDeleteSession->execute();
    }
}

return new SessionDB($pdo);
