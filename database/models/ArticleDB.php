<?php

require_once(__DIR__ . '/../pdoOpen.php');

class ArticleDB
{
    private PDOStatement $statementFetchAll;
    private PDOStatement $statementFetchByDomain;
    private PDOStatement $statementFetchOneById;
    private PDOStatement $statementInsert;
    private PDOStatement $statementUpdate;
    private PDOStatement $statementDelete;

    function __construct(private $pdo)
    {
        $this->statementFetchByDomain = $this->pdo->prepare("SELECT article.id, article.title, article.contain, article.picture, article.author, domain.name, domain.color 
                                                              FROM article JOIN domain ON article.domain=domain.idDomain 
                                                            WHERE domain=:domain");

        $this->statementFetchAll = $this->pdo->prepare("SELECT article.id, article.title, article.contain, article.picture, article.author, domain.name, domain.color 
                                                        FROM article JOIN domain ON article.domain=domain.idDomain
                                                        ORDER BY domain.IdDomain");

        $this->statementFetchOneById = $this->pdo->prepare("SELECT article.id, article.title, article.contain, article.picture, article.author, domain.name, domain.color 
                                                           FROM article JOIN domain ON article.domain=domain.idDomain 
                                                           WHERE article.id=:id");

        $this->statementInsert = $this->pdo->prepare("INSERT INTO article (id, title, contain, picture, author, domain)
                                                        VALUES (DEFAULT,:title,:contain,:picture, :author, :domain)");

        $this->statementUpdate = $this->pdo->prepare("UPDATE article 
                                                        SET title=:title,
                                                            contain=:contain,
                                                            picture=:picture,
                                                            domain=:domain
                                                        WHERE id=:id");

        $this->statementDelete = $this->pdo->prepare("DELETE FROM article WHERE id=:id");
    }

    function selectByDomain($domain)
    {
        $this->statementFetchByDomain->bindValue(':domain', $domain);
        $this->statementFetchByDomain->execute();

        return $this->statementFetchByDomain->fetchAll();
    }

    function selectAll()
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    function selectById($id)
    {
        $this->statementFetchOneById->bindValue(':id', $id);
        $this->statementFetchOneById->execute();
        return $this->statementFetchOneById->fetch();
    }



    function insertArticle($title, $contain, $picture, $author, $idDomain)
    {
        $this->statementInsert->bindValue(':title', $title);
        $this->statementInsert->bindValue(':contain', $contain);
        $this->statementInsert->bindValue(':picture', $picture);
        $this->statementInsert->bindValue(':author', $author);
        $this->statementInsert->bindValue(':domain', $idDomain);
        $this->statementInsert->execute();
    }

    function updateArticle($title, $contain, $picture, $idDomain, $id)
    {
        $this->statementUpdate->bindValue(':title', $title);
        $this->statementUpdate->bindValue(':contain', $contain);
        $this->statementUpdate->bindValue(':picture', $picture);
        $this->statementUpdate->bindValue(':domain', $idDomain);
        $this->statementUpdate->bindValue(':id', $id);
        $this->statementUpdate->execute();
    }

    function deleteArticle($id)
    {


        $this->statementDelete->bindValue(':id', $id);
        $this->statementDelete->execute();
        return $id;
    }
}


return new ArticleDB($pdo);
