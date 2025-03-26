<?php

require_once __DIR__ . '/../config/db.php';

class Alarm
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT a.*, e.name AS equipment_name 
                                   FROM alarms a 
                                   JOIN equipment e ON a.equipment_id = e.id
                                   ORDER BY a.created_at DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO alarms (description, classification, equipment_id, created_at, status)
            VALUES (:description, :classification, :equipment_id, NOW(), 'off')
        ");

        return $stmt->execute([
            'description' => $data['description'],
            'classification' => $data['classification'],
            'equipment_id' => $data['equipment_id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM alarms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function activate($id)
    {
        try {
            $this->conn->beginTransaction();
    
            // 1. Atualiza o status do alarme para 'on'
            $stmt = $this->conn->prepare("
                UPDATE alarms 
                SET status = 'on' 
                WHERE id = :id AND status = 'off'
            ");
            $stmt->execute(['id' => $id]);
    
            // Verifica se alguma linha foi afetada
            if ($stmt->rowCount() === 0) {
                $this->conn->rollBack();
                return false;
            }
    
            // 2. Cria um novo registro de atividade
            $stmt = $this->conn->prepare("
                INSERT INTO alarm_activity (alarm_id, status, started_at)
                VALUES (:alarm_id, 'active', NOW())
            ");
            $stmt->execute(['alarm_id' => $id]);
    
            $this->conn->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error activating alarm: " . $e->getMessage());
            return false;
        }
    }
    public function deactivate($id)
    {
        try {
            $this->conn->beginTransaction();
    
            // 1. Verifica se o alarme existe e está ativo
            $stmt = $this->conn->prepare("SELECT id, status FROM alarms WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $alarm = $stmt->fetch();
    
            if (!$alarm) {
                throw new Exception("Alarm not found");
            }
    
            if ($alarm['status'] !== 'on') {
                throw new Exception("Alarm is not active");
            }
    
            // 2. Encontra a atividade ativa mais recente ou cria uma se não existir
            $stmt = $this->conn->prepare("
                SELECT id FROM alarm_activity 
                WHERE alarm_id = :alarm_id 
                AND (status = 'active' OR ended_at IS NULL)
                ORDER BY started_at DESC 
                LIMIT 1
            ");
            $stmt->execute(['alarm_id' => $id]);
            $activity = $stmt->fetch();
    
            if (!$activity) {
                // Se não existir atividade, cria uma nova antes de desativar
                $stmt = $this->conn->prepare("
                    INSERT INTO alarm_activity (alarm_id, status, started_at, ended_at)
                    VALUES (:alarm_id, 'inactive', NOW(), NOW())
                ");
                $stmt->execute(['alarm_id' => $id]);
                $activityId = $this->conn->lastInsertId();
            } else {
                $activityId = $activity['id'];
            }
    
            // 3. Atualiza o status do alarme
            $stmt = $this->conn->prepare("UPDATE alarms SET status = 'off' WHERE id = :id");
            $stmt->execute(['id' => $id]);
    
            // 4. Atualiza a atividade
            $stmt = $this->conn->prepare("
                UPDATE alarm_activity 
                SET status = 'inactive', 
                    ended_at = NOW() 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $activityId]);
    
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Deactivation failed: " . $e->getMessage());
            return false;
        }
    }
public function getTriggeredAlarms($filters = [], $orderBy = 'started_at', $orderDir = 'DESC')
{
    $query = "SELECT 
                aa.id,
                a.description AS alarm_description,
                a.classification,
                e.name AS equipment_name,
                e.serial_number,
                aa.started_at,
                aa.ended_at,
                aa.status,
                CASE 
                    WHEN aa.ended_at IS NULL THEN TIMESTAMPDIFF(SECOND, aa.started_at, NOW())
                    ELSE TIMESTAMPDIFF(SECOND, aa.started_at, aa.ended_at)
                END AS duration_seconds
              FROM alarm_activity aa
              JOIN alarms a ON aa.alarm_id = a.id
              JOIN equipment e ON a.equipment_id = e.id
              WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['description'])) {
            $query .= " AND a.description LIKE :description";
            $params['description'] = '%' . $filters['description'] . '%';
        }
        
        if (!empty($filters['equipment'])) {
            $query .= " AND e.name LIKE :equipment";
            $params['equipment'] = '%' . $filters['equipment'] . '%';
        }
        
        if (!empty($filters['status'])) {
            $query .= " AND aa.status = :status";
            $params['status'] = $filters['status'];
        }
        
        $validColumns = ['started_at', 'ended_at', 'duration_seconds', 'alarm_description', 'equipment_name'];
        $orderBy = in_array($orderBy, $validColumns) ? $orderBy : 'started_at';
        $orderDir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        
        $query .= " ORDER BY {$orderBy} {$orderDir}";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar alarmes atuados: " . $e->getMessage());
            return false;
        }
    }
    public function getById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT a.*, e.name AS equipment_name 
            FROM alarms a
            JOIN equipment e ON a.equipment_id = e.id
            WHERE a.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMostTriggered($limit = 3)
    {
        $stmt = $this->conn->prepare("
            SELECT a.description, COUNT(ac.id) AS trigger_count
            FROM alarm_activity ac
            JOIN alarms a ON ac.alarm_id = a.id
            WHERE ac.status = 'active'
            GROUP BY a.description
            ORDER BY trigger_count DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}