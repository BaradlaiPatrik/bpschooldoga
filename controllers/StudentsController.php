<?php
require_once "models/StudentsModel.php";
require_once "views/StudentsView.php";

class StudentsController
{
    private StudentsModel $model;
    public function __construct(PDO $pdo)
    {
        $this->model = new StudentsModel($pdo);
    }
    public function handleRequest(string $view)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-student'])) {
                $this->model->create(
                    $_POST['name'],
                    $_POST['birth_date'],
                    $_POST['class_id'] ?? null
                );
                header("Location: index.php?view=students");
                exit;
            }
            if (isset($_POST['update-student'])) {
                $this->model->update(
                    $_POST['id'],
                    $_POST['name'],
                    $_POST['birth_date'],
                    $_POST['class_id'] ?? null
                );
                header("Location: index.php?view=students");
                exit;
            }
        }
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            header("Location: index.php?view=students");
            exit;
        }
        switch ($view) {

            case 'students':
                $students = $this->model->getAll();
                StudentsView::list($students);
                break;

            case 'add-student':
                $classes = $this->model->getAllClassesForSelect();
                StudentsView::addForm($classes);
                break;

            case 'edit-student':
                $student = $this->model->find($_GET['id'] ?? 0);
                if ($student) {
                    $classes = $this->model->getAllClassesForSelect();
                    StudentsView::editForm($student, $classes);
                } else {
                    header("Location: index.php?view=students");
                    exit;
                }
                break;
        }
    }
}