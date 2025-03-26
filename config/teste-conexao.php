<?php
require '../config/db.php';
$db = new Database();
if ($db->getConnection()) {
    echo "✅ Conexão com o banco de dados funcionando!";
} else {
    echo "❌ Erro na conexão";
}