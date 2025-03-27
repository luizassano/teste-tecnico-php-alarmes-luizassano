<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'alarm_system';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("ERRO GRAVE: Não foi possível conectar ao banco de dados. 
                    Contate o administrador. Erro técnico: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}