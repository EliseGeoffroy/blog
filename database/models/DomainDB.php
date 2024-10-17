<?php
require_once(__DIR__ . '/../pdoOpen.php');

class DomainDB
{

    private PDOStatement $statementFetchAll;
    private PDOStatement $statementInsertDomain;
    private PDOStatement $statementFetchByAuthor;

    function __construct(private $pdo)
    {
        $this->statementFetchAll = $this->pdo->prepare("SELECT domain.name, idDomain, count(*) as number from article join domain on idDomain=domain group by domain ");
        $this->statementInsertDomain = $this->pdo->prepare("INSERT INTO domain VALUES (DEFAULT,:name,:color)");
        $this->statementFetchByAuthor = $this->pdo->prepare("SELECT domain.name, idDomain, count(*) as number from article join domain on idDomain=domain where   author=:author group by domain ");
    }


    function selectAll()
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    function selectByAuthor($author)
    {
        $this->statementFetchByAuthor->bindValue(':author', $author);
        $this->statementFetchByAuthor->execute();
        return $this->statementFetchByAuthor->fetchAll();
    }

    function insertDomain($name, $color)
    {
        $this->statementInsertDomain->bindValue(':name', $name);
        $this->statementInsertDomain->bindValue(':color', $color);
        $this->statementInsertDomain->execute();
        return $this->pdo->lastInsertId();
    }
}

return new DomainDB($pdo);
