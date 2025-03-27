<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/EquipmentController.php';
require_once __DIR__ . '/controllers/AlarmController.php';
require_once __DIR__ . '/controllers/AlarmActivityController.php';

$route = $_GET['route'] ?? 'equipment';

switch ($route) {
    case 'equipment':
        $controller = new EquipmentController();
        $controller->index();
        break;

    case 'equipment/create':
        $controller = new EquipmentController();
        $controller->create();
        break;

    case 'equipment/edit':
        if (isset($_GET['id'])) {
            $controller = new EquipmentController();
            $controller->edit($_GET['id']);
        } else {
            header("Location: " . BASE_URL . "/?route=equipment");
        }
        break;

    case 'equipment/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $controller = new EquipmentController();
            $controller->update();
        } else {
            header("Location: " . BASE_URL . "/?route=equipment");
        }
        break;

    case 'equipment/delete':
        if (isset($_GET['id'])) {
            $controller = new EquipmentController();
            $controller->delete($_GET['id']);
        } else {
            header("Location: " . BASE_URL . "/?route=equipment");
        }
        break;

    case 'alarm':
        $controller = new AlarmController();
        $controller->index();
        break;

    case 'alarm/create':
        $controller = new AlarmController();
        $controller->create();
        break;

    case 'alarm/activate':
        if (isset($_GET['id'])) {
            $controller = new AlarmController();
            $controller->activate($_GET['id']);
        } else {
            header("Location: " . BASE_URL . "/?route=alarm");
        }
        break;

    case 'alarm/deactivate':
        if (isset($_GET['id'])) {
            $controller = new AlarmController();
            $controller->deactivate($_GET['id']);
        } else {
            header("Location: " . BASE_URL . "/?route=alarm");
        }
        break;

    case 'alarm/delete':
        if (isset($_GET['id'])) {
            $controller = new AlarmController();
            $controller->delete($_GET['id']);
        } else {
            header("Location: " . BASE_URL . "/?route=alarm");
        }
        break;

    case 'alarm/edit':
        if (isset($_GET['id'])) {
            $controller = new AlarmController();
            $controller->edit($_GET['id']);
        }
        break;

    case 'alarm/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AlarmController();
            $controller->update();
        }
        break;

    case 'alarm-activity':
        $controller = new AlarmActivityController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        if (file_exists(__DIR__ . '/views/errors/404.php')) {
            include __DIR__ . '/views/errors/404.php';
        } else {
            echo "<h1>404 Not Found</h1>";
            echo "<p>The page you requested was not found.</p>";
        }
        exit;
        break;
}