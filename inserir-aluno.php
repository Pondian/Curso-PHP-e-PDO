<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Domain\Model\Student;
require_once 'vendor/autoload.php';


$pdo = ConnectionCreator::createConnection();

$student = new Student(null, 'Willian', new \DateTimeImmutable('1997-10-15'));

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ? );";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(1, $student->name());
$statement->bindValue(2, $student->birthDate()->format('Y-m-d'));
$statement->execute();
var_dump($pdo ->exec($sqlInsert));