<?php
require_once "views/LayoutView.php";
// require_once "views/HomeView.php";
// require_once "views/SubjectView.php";
// require_once "models/SubjectModel.php";
// require_once "controllers/SubjectController.php";
require_once "controllers/Router.php";
require_once "views/StudentsView.php";
require_once "models/StudentsModel.php";
require_once "controllers/StudentsController.php";

$pdo = new PDO("mysql:host=localhost;dbname=bp_school;charset=utf8", "root", "");

$view = $_GET['view'] ?? 'home';
$router = new Router($pdo);

LayoutView::head();
LayoutView::menu();

$router->handle($view);

LayoutView::footer();
