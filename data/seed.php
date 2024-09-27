<?php

$arrayArticleIndex = json_decode(file_get_contents('./articleIndex.json'), true);

$arrayDomainIndex = json_decode(file_get_contents('./DomainsIndex.json'), true);

require_once('../database/pdoOpen.php');




//table domain

$statement = $pdo->prepare("CREATE TABLE `blog`.`domain` (
        `idDomain` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(45) NOT NULL,
        `color` VARCHAR(45) NOT NULL,
        PRIMARY KEY (`idDomain`));");
$statement->execute();

$statement = $pdo->prepare("INSERT INTO domain (idDomain, name, color) VALUES (DEFAULT, :name, :color)");


$name = '';
$color = '';

$statement->bindParam(':name', $name);
$statement->bindParam(':color', $color);


foreach ($arrayDomainIndex as $domain) {
    $name = $domain['name'];
    $color = 'rgb(' . $domain['color']['r'] . ',' . $domain['color']['g'] . ',' . $domain['color']['b'] . ')';
    $statement->execute();
}

//table article


$statement = $pdo->prepare("CREATE TABLE `blog`.`article` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(45) NOT NULL,
    `contain` LONGTEXT NOT NULL,
    `picture` VARCHAR(100) NOT NULL,
    `domain` INT NOT NULL,
    PRIMARY KEY (`id`))
    ;");

$statement->execute();

$statement = $pdo->prepare("ALTER TABLE `blog`.`article` 
ADD CONSTRAINT `FK_article_domain_id`
FOREIGN KEY (`domain`)
REFERENCES `blog`.`domain` (`idDomain`)
ON DELETE NO ACTION
ON UPDATE NO ACTION;");
$statement->execute();

$statement = $pdo->prepare("INSERT INTO article (id,title,contain,picture,domain) VALUES (DEFAULT, :title, :contain,:picture,:domain)");

$title = '';
$contain = '';
$picture = '';
$domain = '';

$statement->bindParam(':title', $title);
$statement->bindParam(':contain', $contain);
$statement->bindParam(':picture', $picture);
$statement->bindParam(':domain', $domain);

foreach ($arrayArticleIndex as $article) {
    $title = $article['title'];
    $contain = $article['contain'];
    $picture = $article['picture'];
    $domain = searchDomain($article, $pdo);

    $statement->execute();
}

function searchDomain($article, $pdo)
{
    $selectStatement = $pdo->prepare("SELECT idDomain FROM domain WHERE name=:name");
    $selectStatement->bindValue(':name', $article['domain']);
    $selectStatement->execute();
    $domain = $selectStatement->fetch();


    return $domain['idDomain'];
}
