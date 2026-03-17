<h2>🔍 Buscar Aluno pelo CPF</h2>

<form action="index.php" method="get">
    <label for="cpf">CPF/CNPJ:</label>
    <input type="text" 
           name="cpf" 
           id="cpf"
           placeholder="Digite o CPF (apenas números)" 
           maxlength="14"
           required>
    <button type="submit">Buscar</button>
</form>

<p><small>Digite apenas números. Ex: 12345678900</small></p>