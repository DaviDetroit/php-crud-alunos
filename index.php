<?php

require 'config.php';

$sql = 'SELECT * FROM alunos';
$stmt = $pdo->query($sql);
$alunos = $stmt->fetchAll();

?>

<h2> Lista de Alunos</h2>
<a href="cadastrar.php">Cadastrar novo aluno</a>

<?php foreach ($alunos as $aluno): ?>
<p>ID: <?php echo $aluno['id']; ?> | Nome: <?php echo $aluno['nome']; ?>| Email: <?php echo $aluno['email']; ?></p>
<?php endforeach; ?>