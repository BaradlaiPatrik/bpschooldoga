<?php
require_once "models/ClassModel.php";
require_once "models/MarksModel.php";
require_once "views/ListsView.php";
class ListsController
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function handleRequest(string $view)
    {
        if (isset($_GET['class_id']) && is_numeric($_GET['class_id'])) {
            $classId = (int)$_GET['class_id'];

            $classModel = new ClassModel($this->pdo);
            $class = $classModel->find($classId);
            if (!$class) {
                header("Location: index.php?view=lists");
                exit;
            }
            $marksModel = new MarksModel($this->pdo);
            $students = $marksModel->getClassStudents($classId);

            foreach ($students as &$student) {
                $student['subject_avgs'] = $marksModel->getStudentSubjectAverages($student['id']);
            }
            $overall = $marksModel->getClassOverallAverage($classId);
            $subjectAvgs = $marksModel->getClassSubjectAverages($classId);

            ListsView::classList($class, $students, $overall, $subjectAvgs);
        } else {
            $classes = $this->pdo
                ->query("SELECT id, CONCAT(year, ' - ', grade, '. ', letter) AS display 
                         FROM classes 
                         ORDER BY year DESC, grade ASC, letter ASC")
                ->fetchAll(PDO::FETCH_ASSOC);
            ListsView::selectForm($classes);
        }
    }
}