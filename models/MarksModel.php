<?php

class MarksModel
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAll()
    {
        return $this->pdo
            ->query("SELECT m.id, m.mark, m.date, s.name AS student_name, sub.name AS subject_name
                FROM marks m
                JOIN students s ON m.student_id = s.id
                JOIN subjects sub ON m.subject_id = sub.id
                ORDER BY m.date DESC, s.name ASC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM marks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($student_id, $subject_id, $mark, $date)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO marks (student_id, subject_id, mark, date) 
             VALUES (:student_id, :subject_id, :mark, :date)"
        );
        $stmt->execute([
            'student_id'  => $student_id,
            'subject_id'  => $subject_id,
            'mark'        => $mark,
            'date'        => $date,
        ]);
    }

    public function update($id, $student_id, $subject_id, $mark, $date)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE marks 
             SET student_id = :student_id, 
                 subject_id = :subject_id, 
                 mark = :mark, 
                 date = :date 
             WHERE id = :id"
        );
        $stmt->execute([
            'id'          => $id,
            'student_id'  => $student_id,
            'subject_id'  => $subject_id,
            'mark'        => $mark,
            'date'        => $date,
        ]);
    }
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM marks WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    public function getAllStudentsForSelect()
    {
        return $this->pdo
            ->query("SELECT id, name FROM students ORDER BY name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllSubjectsForSelect()
    {
        return $this->pdo
            ->query("SELECT id, name FROM subjects ORDER BY name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getClassStudents($class_id)
    {
        $stmt = $this->pdo->prepare("SELECT s.id, s.name, ROUND(AVG(m.mark), 2) AS student_avg
            FROM students s LEFT JOIN marks m ON s.id = m.student_id
            WHERE s.class_id = :class_id GROUP BY s.id
            ORDER BY s.name ASC
        ");
        $stmt->execute(['class_id' => $class_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getClassOverallAverage($class_id)
    {
        $stmt = $this->pdo->prepare(" SELECT ROUND(AVG(m.mark), 2) AS overall_avg
            FROM marks m JOIN students s ON m.student_id = s.id
            WHERE s.class_id = :class_id
        ");
        $stmt->execute(['class_id' => $class_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['overall_avg'] ?? 0;
    }
    public function getClassSubjectAverages($class_id)
    {
        $stmt = $this->pdo->prepare(" SELECT sub.name AS subject_name, ROUND(AVG(m.mark), 2) AS avg
            FROM marks m JOIN students s ON m.student_id = s.id JOIN subjects sub ON m.subject_id = sub.id
            WHERE s.class_id = :class_id GROUP BY sub.id ORDER BY sub.name
        ");
        $stmt->execute(['class_id' => $class_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStudentSubjectAverages($student_id)
    {
        $stmt = $this->pdo->prepare(" SELECT sub.name AS subject_name, ROUND(AVG(m.mark), 2) AS avg
            FROM marks m JOIN subjects sub ON m.subject_id = sub.id WHERE m.student_id = :student_id
            GROUP BY sub.id ORDER BY sub.name
        ");
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}