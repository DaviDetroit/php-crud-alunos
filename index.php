<?php
require 'config.php'; // arquivo com $pdo configurado

$cadastros = [];
$mensagem = '';

if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
    $cpf_input = $_GET['cpf'];
    
    // Remove qualquer formatação do CPF/CNPJ digitado
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf_input);
    
    try {
        // Validação básica
        if (strlen($cpf_limpo) < 11 || strlen($cpf_limpo) > 14) {
            $mensagem = "Documento inválido! Digite CPF (11 números) ou CNPJ (14 números).";
        } else {
            // Chama a procedure
            $stmt = $pdo->prepare("CALL sp_get_cadastro_completo_by_documento(?)");
            $stmt->execute([$cpf_limpo]);
            $cadastros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($cadastros)) {
                $cadastros_agrupados = [];
                foreach ($cadastros as $cadastro) {
                    $cpf = $cadastro['cpf'];
                    // Se não existe ou se este é mais recente, adiciona/substitui
                    if (!isset($cadastros_agrupados[$cpf]) || 
                        $cadastro['submitted_at'] > $cadastros_agrupados[$cpf]['submitted_at']) {
                        $cadastros_agrupados[$cpf] = $cadastro;
                    }
                }
                $cadastros = array_values($cadastros_agrupados); // Reindexa o array
            }
            
            if (empty($cadastros)) {
                $mensagem = "Nenhum cadastro encontrado para: " . htmlspecialchars($cpf_input);
            } else {
                $mensagem = count($cadastros) . " cadastro(s) encontrado(s).";
            }
        }
    } catch (PDOException $e) {
        $mensagem = "Erro na consulta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Busca de Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .busca { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .resultado { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .cadastro { border-bottom: 1px solid #eee; padding: 15px 0; }
        .cadastro:last-child { border-bottom: none; }
        .tipo { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .tipo-pf { background: #e3f2fd; color: #1976d2; }
        .tipo-pj { background: #f3e5f5; color: #7b1fa2; }
        .tipo-outro { background: #eeeeee; color: #616161; }
        .mensagem { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .erro { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .info { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
        .sucesso { background: #e8f5e8; color: #2e7d32; border: 1px solid #c8e6c9; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .campo { margin: 5px 0; }
        .campo strong { display: inline-block; min-width: 150px; color: #666; }
        .aceite-sim { color: #2e7d32; font-weight: bold; }
        .aceite-nao { color: #c62828; font-weight: bold; }
        h3 { color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #f0f0f0; padding-bottom: 5px; }
        input[type="text"] { padding: 8px; width: 300px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        button { padding: 8px 20px; background: #1976d2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #1565c0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="busca">
            <h2>🔍 Buscar Cadastro</h2>
            <form action="" method="get">
                <label for="cpf">CPF/CNPJ:</label>
                <input type="text" 
                       id="cpf" 
                       name="cpf" 
                       placeholder="Digite CPF (11 números) ou CNPJ (14 números)" 
                       value="<?= isset($_GET['cpf']) ? htmlspecialchars($_GET['cpf']) : '' ?>"
                       maxlength="18"
                       required>
                <button type="submit">Buscar</button>
            </form>
        </div>

        <?php if ($mensagem): ?>
            <div class="mensagem <?= 
                strpos($mensagem, 'Erro') !== false ? 'erro' : 
                (strpos($mensagem, 'Nenhum') !== false ? 'info' : 'sucesso') 
            ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cadastros)): ?>
            <div class="resultado">
                <h2>📋 Resultados Encontrados</h2>
                
                <?php foreach ($cadastros as $cadastro): ?>
                    <div class="cadastro">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3 style="margin:0;">
                                Registro #<?= htmlspecialchars($cadastro['submission_id']) ?>
                                <span class="tipo tipo-<?= strtolower($cadastro['tipo_cadastro']) ?>">
                                    <?= htmlspecialchars($cadastro['tipo_cadastro']) ?>
                                </span>
                            </h3>
                            <small>Data: <?= date('d/m/Y H:i', strtotime($cadastro['submitted_at'])) ?></small>
                        </div>

                        <div class="grid">
                            <!-- Coluna 1: Dados Pessoais -->
                            <div>
                                <h4>👤 Dados Pessoais</h4>
                                <?php if (!empty($cadastro['nome'])): ?>
                                    <div class="campo"><strong>Nome:</strong> <?= htmlspecialchars($cadastro['nome']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['cpf'])): ?>
                                    <div class="campo"><strong>CPF:</strong> <?= htmlspecialchars($cadastro['cpf']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['nome_mae'])): ?>
                                    <div class="campo"><strong>Nome da Mãe:</strong> <?= htmlspecialchars($cadastro['nome_mae']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['nome_pai'])): ?>
                                    <div class="campo"><strong>Nome do Pai:</strong> <?= htmlspecialchars($cadastro['nome_pai']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['nome_responsavel'])): ?>
                                    <div class="campo"><strong>Responsável:</strong> <?= htmlspecialchars($cadastro['nome_responsavel']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Coluna 2: Dados PJ/Profissional -->
                            <div>
                                <?php if (!empty($cadastro['razao_social']) || !empty($cadastro['cnpj'])): ?>
                                    <h4>🏢 Dados Profissionais</h4>
                                    <?php if (!empty($cadastro['razao_social'])): ?>
                                        <div class="campo"><strong>Razão Social:</strong> <?= htmlspecialchars($cadastro['razao_social']) ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($cadastro['cnpj'])): ?>
                                        <div class="campo"><strong>CNPJ:</strong> <?= htmlspecialchars($cadastro['cnpj']) ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($cadastro['alvara_pj'])): ?>
                                        <div class="campo"><strong>Alvará:</strong> <?= htmlspecialchars($cadastro['alvara_pj']) ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Coluna 3: Contato -->
                            <div>
                                <h4>📧 Contato</h4>
                                <?php if (!empty($cadastro['email_pessoal'])): ?>
                                    <div class="campo"><strong>Email Pessoal:</strong> <?= htmlspecialchars($cadastro['email_pessoal']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['email_profissional'])): ?>
                                    <div class="campo"><strong>Email Profissional:</strong> <?= htmlspecialchars($cadastro['email_profissional']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['telefone_celular'])): ?>
                                    <div class="campo"><strong>Celular:</strong> <?= htmlspecialchars($cadastro['telefone_celular']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['telefone_comercial'])): ?>
                                    <div class="campo"><strong>Comercial:</strong> <?= htmlspecialchars($cadastro['telefone_comercial']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['telefone_fixo'])): ?>
                                    <div class="campo"><strong>Fixo:</strong> <?= htmlspecialchars($cadastro['telefone_fixo']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['telefone_responsavel'])): ?>
                                    <div class="campo"><strong>Tel. Responsável:</strong> <?= htmlspecialchars($cadastro['telefone_responsavel']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Coluna 4: Registros -->
                            <div>
                                <h4>📄 Registros</h4>
                                <?php 
                                // Lógica para o aceite
                                $aceite = $cadastro['aceite_filiacao'] ?? '';
                                $aceite_status = '';
                                $aceite_class = '';
                                
                                if (empty($aceite) || $aceite === '0' || $aceite === 'false' || $aceite === 'não' || $aceite === 'nao') {
                                    $aceite_status = '❌ NÃO CONCORDOU COM OS TERMOS';
                                    $aceite_class = 'aceite-nao';
                                } else {
                                    $aceite_status = '✅ CONCORDOU COM OS TERMOS';
                                    $aceite_class = 'aceite-sim';
                                }
                                ?>
                                <div class="campo <?= $aceite_class ?>">
                                    <strong>Aceite de Filiação:</strong> 
                                    <?= $aceite_status ?>
                                    <?php if (!empty($aceite) && $aceite !== '0' && $aceite !== 'false' && $aceite !== 'não' && $aceite !== 'nao'): ?>
                                        <small>(<?= htmlspecialchars($aceite) ?>)</small>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($cadastro['registro_pf'])): ?>
                                    <div class="campo"><strong>Registro PF:</strong> <?= htmlspecialchars($cadastro['registro_pf']) ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($cadastro['registro_responsavel'])): ?>
                                    <div class="campo"><strong>Registro Responsável:</strong> <?= htmlspecialchars($cadastro['registro_responsavel']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Alerta especial se não concordou -->
                        <?php if (empty($aceite) || $aceite === '0' || $aceite === 'false' || $aceite === 'não' || $aceite === 'nao'): ?>
                            <div style="margin-top: 15px; padding: 10px; background: #ffebee; border: 1px solid #ffcdd2; border-radius: 4px;">
                                <strong style="color: #c62828;">⚠️ ATENÇÃO:</strong> 
                                <span style="color: #c62828;">Este cadastrado não concordou com os termos de filiação!</span>
                            </div>
                        <?php else: ?>
                            <div style="margin-top: 15px; padding: 10px; background: #e8f5e8; border: 1px solid #c8e6c9; border-radius: 4px;">
                                <strong style="color: #2e7d32;">✅ CONCORDOU:</strong> 
                                <span style="color: #2e7d32;">Este cadastrado concordou com os termos de filiação.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>