<?php

require_once 'vendor/autoload.php';

$pdo = new PDO('sqlite:banco.sqlite');

$preparedStatement = $pdo->prepare('DELETE FROM students WHERE id = ?;');
$preparedStatement->bindValue(1, 3, PDO::PARAM_INT);
var_dump($preparedStatement->execute());
