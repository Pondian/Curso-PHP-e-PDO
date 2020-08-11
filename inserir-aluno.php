<?php

use Alura\Pdo\Domain\Model\Student;
require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:'.$databasePath);

$student = new Student(null, 'Willian', new \DateTimeImmutable('1997-10-15'));

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES ('{$student->name()}','{$student->birthDate()->format('y-m-d')}');";

var_dump($pdo ->exec($sqlInsert));