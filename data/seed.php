<?php

require_once('../database/pdoOpen.php');

$statement = $pdo->prepare("DROP TABLE article");
$statement->execute();

$statement = $pdo->prepare("DROP TABLE domain");
$statement->execute();

$statement = $pdo->prepare("DROP TABLE user");
$statement->execute();

$statement = $pdo->prepare("DROP TABLE session");
$statement->execute();


//table domain
$arrayDomainIndex = json_decode(file_get_contents('./DomainsIndex.json'), true);

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
$arrayArticleIndex = json_decode(file_get_contents('./articleIndex.json'), true);

$statement = $pdo->prepare("CREATE TABLE `blog`.`article` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(45) NOT NULL,
    `contain` LONGTEXT NOT NULL,
    `picture` VARCHAR(100) NOT NULL,
    `domain` INT NOT NULL,
    `author` VARCHAR(45) NOT NULL,
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


$statement = $pdo->prepare("INSERT INTO article (id,title,contain,picture,author,domain) VALUES (DEFAULT, :title, :contain,:picture,:author,:domain)");

$title = '';
$contain = '';
$picture = '';
$domain = '';
$author = '';

$statement->bindParam(':title', $title);
$statement->bindParam(':contain', $contain);
$statement->bindParam(':picture', $picture);
$statement->bindParam(':domain', $domain);
$statement->bindParam(':author', $author);

foreach ($arrayArticleIndex as $article) {
    $title = $article['title'];
    $contain = $article['contain'];
    $picture = $article['picture'];
    $domain = searchDomain($article, $pdo);
    $author = $article['author'];

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

//table user
$arrayUser = json_decode(file_get_contents('./user.json'), true);



$statement = $pdo->prepare("CREATE TABLE `blog`.`user` (
    `idUser` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idUser`))
    ;");

$statement->execute();


$statement = $pdo->prepare("INSERT INTO user (idUser,name,email,password) VALUES (DEFAULT, :name,:email,:password)");

$name = '';
$email = '';
$password = '';


$statement->bindParam(':name', $name);
$statement->bindParam(':email', $email);
$statement->bindParam(':password', $password);


foreach ($arrayUser as $user) {
    $name = $user['name'];
    $email = $user['email'];
    $password = $user['password'];

    $statement->execute();
}

$statement = $pdo->prepare("ALTER TABLE `blog`.`article` 
ADD CONSTRAINT `FK_article_user_author`
FOREIGN KEY (`author`)
REFERENCES `blog`.`user` (`name`)
ON DELETE NO ACTION
ON UPDATE NO ACTION;");
$statement->execute();

//table session
$statement = $pdo->prepare("CREATE TABLE `blog`.`session` (
    `idSession` CHAR(64) NOT NULL,
    `idUser` INT NOT NULL,
    PRIMARY KEY (`idSession`))
    ;");

$statement->execute();
