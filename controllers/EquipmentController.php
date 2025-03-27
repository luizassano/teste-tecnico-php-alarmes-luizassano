<?php

require_once __DIR__ . '/../models/Equipment.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/../config.php';

class EquipmentController
{
    private $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new Equipment();
    }

    public function index()
    {
        $equipments = $this->equipmentModel->getAll();
        require __DIR__ . '/../view/equipments/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'serial_number' => $_POST['serial_number'] ?? '',
                'type' => $_POST['type'] ?? ''
            ];

            if ($this->equipmentModel->create($data)) {
                Logger::log('Created equipment: ' . $data['name']);
                header('Location: ' . BASE_URL . '/?route=equipment');
                exit;
            } else {
                $error = "Failed to create equipment.";
            }
        }

        require __DIR__ . '/../view/equipments/create.php';
    }

    public function edit($id)
    {
        $equipment = $this->equipmentModel->getById($id);
        require __DIR__ . '/../view/equipments/update.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'name' => $_POST['name'] ?? '',
                'serial_number' => $_POST['serial_number'] ?? '',
                'type' => $_POST['type'] ?? ''
            ];

            if ($id && $this->equipmentModel->update($id, $data)) {
                Logger::log('Updated equipment ID: ' . $id);
                header('Location: ' . BASE_URL . '/?route=equipment');
                exit;
            }
        }

        header('Location: ' . BASE_URL . '/?route=equipment');
        exit;
    }


    public function delete($id)
    {
        if ($this->equipmentModel->delete($id)) {
            Logger::log("Deleted equipment ID: $id");
        }

        header('Location: ' . BASE_URL . '/?route=equipment');
        exit;
    }
}