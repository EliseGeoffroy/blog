<?php

require_once $dir . '/database/pdoOPen.php';

class SessionDB
{
    private PDOStatement $statementInsertSession;
    private PDOStatement $statementDeleteSession;
    private PDOStatement $statementSelectJoin;

    function __construct(private $pdo)
    {
        $this->statementInsertSession = $pdo->prepare("INSERT INTO session(idSession,idUser) VALUES (:idSession,:idUser)");
        $this->statementDeleteSession = $pdo->prepare("DELETE FROM session WHERE idSession=:idSession");
        $this->statementSelectJoin = $pdo->prepare("SELECT user.name FROM session JOIN user ON session.idUser=user.idUser WHERE idSession =:idSession");
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

    function selectJoin($idSession)
    {
        $this->statementSelectJoin->bindValue(':idSession', $idSession);
        $this->statementSelectJoin->execute();

        return $this->statementSelectJoin->fetch();
    }
}

return new SessionDB($pdo);
