<?php
require_once "models/MarksModel.php";
require_once "views/MarksView.php";

class MarksController
{
    private MarksModel $model;
    public function __construct(PDO $pdo)
    {
        $this->model = new MarksModel($pdo);
    }
    public function handleRequest(string $view)
    {
        // --- POST műveletek ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-mark'])) {
                $this->model->create(
                    $_POST['student_id'],
                    $_POST['subject_id'],
                    $_POST['mark'],
                    $_POST['date']
                );
                header("Location: index.php?view=marks");
                exit;
            }
            if (isset($_POST['update-mark'])) {
                $this->model->update(
                    $_POST['id'],
                    $_POST['student_id'],
                    $_POST['subject_id'],
                    $_POST['mark'],
                    $_POST['date']
                );
                header("Location: index.php?view=marks");
                exit;
            }
        }
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            header("Location: index.php?view=marks");
            exit;
        }
        switch ($view) {

            case 'marks':
                $marks = $this->model->getAll();
                MarksView::list($marks);
                break;

            case 'add-mark':
                $students = $this->model->getAllStudentsForSelect();
                $subjects = $this->model->getAllSubjectsForSelect();
                MarksView::addForm($students, $subjects);
                break;

            case 'edit-mark':
                $mark = $this->model->find($_GET['id'] ?? 0);
                if ($mark) {
                    $students = $this->model->getAllStudentsForSelect();
                    $subjects = $this->model->getAllSubjectsForSelect();
                    MarksView::editForm($mark, $students, $subjects);
                } else {
                    header("Location: index.php?view=marks");
                    exit;
                }
                break;
        }
    }
}