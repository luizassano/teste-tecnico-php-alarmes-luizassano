<?php

require_once __DIR__ . '/../models/Alarm.php';
require_once __DIR__ . '/../models/Equipment.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/../helpers/Mailer.php';
require_once __DIR__ . '/../config.php';

class AlarmController
{
    private $alarmModel;
    private $equipmentModel;

    public function __construct()
    {
        $this->alarmModel = new Alarm();
        $this->equipmentModel = new Equipment();
    }

    public function index()
    {
        $alarms = $this->alarmModel->getAll();
        $topAlarms = $this->alarmModel->getMostTriggered();
        $equipments = $this->equipmentModel->getAll();

        require __DIR__ . '/../view/alarms/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'description' => $_POST['description'] ?? '',
                'classification' => $_POST['classification'] ?? '',
                'equipment_id' => $_POST['equipment_id'] ?? ''
            ];

            if ($this->alarmModel->create($data)) {
                Logger::log("Created alarm: {$data['description']}");
                header('Location: ' . BASE_URL . '/?route=alarm');
                exit;
            } else {
                $error = "Failed to create alarm. All fields are required.";
            }
        }

        $equipments = $this->equipmentModel->getAll();
        require __DIR__ . '/../view/alarms/create.php';
    }

    public function activate($id)
    {
        $alarm = $this->alarmModel->getById($id);
    
        if ($alarm && $alarm['status'] === 'off') {
            if ($this->alarmModel->activate($id)) {
                Logger::log("Activated alarm ID $id");
                $_SESSION['success'] = "Alarm activated successfully!";
                
                if ($alarm['classification'] === 'Urgent') {
                    $equipment = $this->equipmentModel->getById($alarm['equipment_id']);
                    $message = "Urgent Alarm Activated:\n";
                    $message .= "Description: {$alarm['description']}\n";
                    $message .= "Equipment: {$equipment['name']}\n";
                    $message .= "Serial: {$equipment['serial_number']}";
                    
                    Mailer::send('admin@example.com', 'URGENT ALARM', $message);
                }
            } else {
                $_SESSION['error'] = "Failed to activate alarm";
            }
        } else {
            $_SESSION['error'] = "Alarm is already active or not found";
        }
    
        header('Location: ' . BASE_URL . '/?route=alarm');
        exit;
    }

    public function deactivate($id)
    {
        try {
            if ($this->alarmModel->deactivate($id)) {
                $_SESSION['success'] = "Alarm deactivated successfully!";
            } else {
                $_SESSION['error'] = "Failed to deactivate alarm";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            error_log("Deactivation error: " . $e->getMessage());
        }
    
        header('Location: ' . BASE_URL . '/?route=alarm');
        exit;
    }

    public function delete($id)
    {
        if ($this->alarmModel->delete($id)) {
            Logger::log("Deleted alarm ID $id");
        }

        header('Location: ' . BASE_URL . '/?route=alarm');
        exit;
    }
}