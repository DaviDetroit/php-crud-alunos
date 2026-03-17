<?php
$host = $_ENV['DB_HOST'] ?? null;
$port = $_ENV['DB_PORT'] ?? null;
$db   = $_ENV['DB_NAME'] ?? null;
$user = $_ENV['DB_USER'] ?? null;
$pass = $_ENV['DB_PASSWORD'] ?? null;

// Validação básica
if (!$host || !$port || !$db || !$user || !$pass) {
    die("Erro: variáveis de ambiente do banco não estão definidas!");
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}