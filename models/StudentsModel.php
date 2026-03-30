<?php

class StudentsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAll()
    {
        return $this->pdo
            ->query("
                SELECT s.id, s.name, s.birth_date, s.class_id,
                       c.grade, c.letter, c.year
                FROM students s
                LEFT JOIN classes c ON s.class_id = c.id
                ORDER BY s.name ASC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($name, $birth_date, $class_id)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, birth_date, class_id) 
             VALUES (:name, :birth_date, :class_id)"
        );
        $stmt->execute([
            'name'       => $name,
            'birth_date' => $birth_date,
            'class_id'   => $class_id ?: null,
        ]);
    }
    public function update($id, $name, $birth_date, $class_id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE students 
             SET name = :name, birth_date = :birth_date, class_id = :class_id 
             WHERE id = :id"
        );
        $stmt->execute([
            'id'         => $id,
            'name'       => $name,
            'birth_date' => $birth_date,
            'class_id'   => $class_id ?: null,
        ]);
    }
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    public function getAllClassesForSelect()
    {
        return $this->pdo
            ->query("SELECT id, CONCAT(grade, '.', letter, ' (', year, ')') AS name 
                     FROM classes 
                     ORDER BY year DESC, grade ASC, letter ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}