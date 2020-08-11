<?php

$databasepath = __DIR__.'/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasepath);

echo 'conectei';

$pdo->exec('CREATE TABLE students(id INTEGER PRIMARY KEY,name TEXT, birth_date TEXT);');