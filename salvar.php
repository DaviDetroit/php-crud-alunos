<?php

require 'config.php';

$nome = $_POST['nome'];
$email = $_POST['email'];

$sql = "INSERT INTO alunos (nome, email) VALUES (:nome, :email)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $nome,
    ':email' => $email
]);

echo "Aluno cadastrado com sucesso! <br>";
echo "<a href='index.php'>Voltar para lista</a>";