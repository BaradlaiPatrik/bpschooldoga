<?php
require_once "controllers/SubjectController.php";
require_once "views/HomeView.php";
class Router
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function handle(string $view): void
        {
            switch ($view) {

                case 'subjects':
                case 'add-subject':
                case 'edit-subject':
                    $controller = new SubjectController($this->pdo);
                    $controller->handleRequest($view);
                    break;

                case 'classes':
                case 'add-class':
                case 'edit-class':
                    require_once "controllers/ClassController.php";
                    $controller = new ClassController($this->pdo);
                    $controller->handleRequest($view);
                    break;

                case 'students':
                case 'add-student':
                case 'edit-student':
                    require_once "controllers/StudentsController.php";
                    $controller = new StudentsController($this->pdo);
                    $controller->handleRequest($view);
                    break;

                    default:
                    HomeView::render();

                case 'marks':
                case 'add-mark':
                case 'edit-mark':
                    require_once "controllers/MarksController.php";
                    $controller = new MarksController($this->pdo);
                    $controller->handleRequest($view);
                    break;

                case 'maintenance':
                    require_once "controllers/MaintenanceController.php";
                    $controller = new MaintenanceController($this->pdo);
                    $controller->handleRequest($view);
                    break;
                    
                case 'lists':
                    require_once "controllers/ListsController.php";
                    $controller = new ListsController($this->pdo);
                    $controller->handleRequest($view);
                    break;    
            }
        }
}
