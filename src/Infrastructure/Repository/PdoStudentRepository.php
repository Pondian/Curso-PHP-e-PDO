<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use DateTimeImmutable;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private \PDO $connection;
    public function __construct()
    {
        $this->connection = ConnectionCreator::createConnection();
    }
    public function allStudents(): array
    {
        $sqlSelect = ('SELECT * FROM studens;');
        $statement = $this->connection->query($sqlSelect);

        return $this->hydrateStudentList($statement);
    }
    public function studentsBirthAt(\DateTimeImmutable $birthDate): array
    {
        $sqlSelect = 'SELECT * FROM students WHERE birth_date = ?;';
        $statement = $this->connection->prepare($sqlSelect);
        $statement->bindValue(1, $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function hydrateStudentList(\PDOStatement $statement): array
    {
        $studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];
        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student($studentData['id'], $studentData['name'], new \DateTimeImmutable($studentData['birth_date']));
        }
        return $studentList;
    }


    public function save(Student $student): bool
    {
        if ($student->id() == null) {
            return $this->insert($student);
        }
        return $this->update($student);
    }

    public function insert(Student $student): bool
    {
        $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date );";
        $statement = $this->connection->prepare($sqlInsert);
        $success = $statement->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
        ]);

        $student->defineId($this->connection->lastInsertId());
        return $success;
    }

    public function update(Student $student): bool
    {
        $sqlUpdate = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;";
        $statement = $this->connection->prepare($sqlUpdate);
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);
        return $statement->execute();
    }

    public function remove(Student $student): bool
    {
        $preparedStatement = $this->connection->prepare('DELETE FROM students WHERE id = ?;');
        $preparedStatement->bindValue(1, $student->id(), PDO::PARAM_INT);

        return $preparedStatement->execute();
    }
}
