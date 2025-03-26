<?php
require_once __DIR__ . '/../models/Alarm.php';
require_once __DIR__ . '/../helpers/Logger.php';

class AlarmActivityController
{
    private $alarmModel;

    public function __construct()
    {
        $this->alarmModel = new Alarm();
    }

    public function index()
    {
        try {
            $baseUrl = BASE_URL;
            $filters = [
                'description' => $_GET['description'] ?? '',
                'equipment' => $_GET['equipment'] ?? '',
                'status' => $_GET['status'] ?? ''
            ];
            
            $orderBy = $_GET['order_by'] ?? 'started_at';
            $orderDir = $_GET['order_dir'] ?? 'DESC';
            
            $triggeredAlarms = $this->alarmModel->getTriggeredAlarms($filters, $orderBy, $orderDir);
            $topAlarms = $this->alarmModel->getMostTriggered(3);
            
            if (!is_array($triggeredAlarms)) {
                $triggeredAlarms = [];
            }
            if (!is_array($topAlarms)) {
                $topAlarms = [];
            }
    
            $sortUrls = [
                'started_at' => $this->buildSortUrl('started_at'),
                'duration_seconds' => $this->buildSortUrl('duration_seconds')
            ];
    
            require __DIR__ . '/../view/alarm_activity/index.php';
    
        } catch (Exception $e) {
            Logger::log("Error in AlarmActivityController: " . $e->getMessage());
            $_SESSION['error'] = "Error loading alarm history";
            header('Location: ' . BASE_URL . '/?route=alarm');
            exit;
        }
    }

    private function buildSortUrl($column)
    {
        $params = [
            'route' => 'alarm-activity',
            'order_by' => $column,
            'order_dir' => ($_GET['order_by'] ?? '') === $column && ($_GET['order_dir'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'
        ];

        if (!empty($_GET['description'])) {
            $params['description'] = $_GET['description'];
        }
        if (!empty($_GET['equipment'])) {
            $params['equipment'] = $_GET['equipment'];
        }
        if (!empty($_GET['status'])) {
            $params['status'] = $_GET['status'];
        }

        return BASE_URL . '/?' . http_build_query($params);
    }
}