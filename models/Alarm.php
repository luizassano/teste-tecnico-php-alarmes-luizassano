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

    public function update($data)
    {
        try {
            $stmt = $this->conn->prepare("
            UPDATE alarms 
            SET description = :description,
                classification = :classification,
                equipment_id = :equipment_id
            WHERE id = :id
        ");

            return $stmt->execute([
                'id' => $data['id'],
                'description' => $data['description'],
                'classification' => $data['classification'],
                'equipment_id' => $data['equipment_id']
            ]);

        } catch (PDOException $e) {
            error_log("Error updating alarm: " . $e->getMessage());
            return false;
        }
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

            $stmt = $this->conn->prepare("
                UPDATE alarms 
                SET status = 'on' 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $id]);

            $stmt = $this->conn->prepare("
                INSERT INTO alarm_activity (alarm_id, started_at)
                VALUES (:alarm_id, NOW())
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

            $stmt = $this->conn->prepare("
                UPDATE alarms 
                SET status = 'off' 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $id]);

            $stmt = $this->conn->prepare("
                SELECT id FROM alarm_activity 
                WHERE alarm_id = :alarm_id 
                AND ended_at IS NULL
                ORDER BY started_at DESC 
                LIMIT 1
            ");
            $stmt->execute(['alarm_id' => $id]);
            $activity = $stmt->fetch();

            if ($activity) {
                $stmt = $this->conn->prepare("
                    UPDATE alarm_activity 
                    SET ended_at = NOW() 
                    WHERE id = :id
                ");
                $stmt->execute(['id' => $activity['id']]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deactivating alarm: " . $e->getMessage());
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
            if ($filters['status'] === 'active') {
                $query .= " AND aa.ended_at IS NULL";
            } else {
                $query .= " AND aa.ended_at IS NOT NULL";
            }
        }

        $validColumns = ['started_at', 'ended_at', 'duration_seconds', 'alarm_description', 'equipment_name'];
        $orderBy = in_array($orderBy, $validColumns) ? $orderBy : 'started_at';
        $orderDir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';

        $query .= " ORDER BY {$orderBy} {$orderDir}";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as &$row) {
                $row['status'] = $row['ended_at'] === null ? 'active' : 'inactive';
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error fetching alarm history: " . $e->getMessage());
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
        try {
            $stmt = $this->conn->prepare("
                SELECT a.description, COUNT(aa.id) AS trigger_count
                FROM alarm_activity aa
                JOIN alarms a ON aa.alarm_id = a.id
                GROUP BY a.description
                ORDER BY trigger_count DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getting most triggered alarms: " . $e->getMessage());
            return [];
        }
    }
}