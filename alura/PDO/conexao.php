<?php

$pdo = new PDO('sqlite:banco.sqlite');

echo "Conectei";

$pdo->exec('CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXt);');