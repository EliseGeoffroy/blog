<?php
require_once(__DIR__ . '/../pdoOpen.php');

class DomainDB
{

    private PDOStatement $statementFetchAll;
    private PDOStatement $statementInsertDomain;
    //  private PDOStatement $statementSelectIdByName;

    function __construct(private $pdo)
    {
        $this->statementFetchAll = $this->pdo->prepare("SELECT idDomain,name FROM domain");
        $this->statementInsertDomain = $this->pdo->prepare("INSERT INTO domain VALUES (DEFAULT,:name,:color)");
        //     $this->statementSelectIdByName = $this->pdo->prepare("SELECT idDomain FROM domain WHERE name=:name");
    }


    function selectAll()
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    function insertDomain($name, $color)
    {
        $this->statementInsertDomain->bindValue(':name', $name);
        $this->statementInsertDomain->bindValue(':color', $color);
        $this->statementInsertDomain->execute();
        return $this->pdo->lastInsertId();
    }

    //     function selectIdByName($name)
    //     {
    //         $this->statementSelectIdByName->bindValue(':name', $name);
    //         $this->statementSelectIdByName->execute();
    //         return $this->statementSelectIdByName->fetchAll();;
    //     }
}

return new DomainDB($pdo);
