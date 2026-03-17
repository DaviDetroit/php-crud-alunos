# 🔍 Sistema de Consulta de Cadastros (PHP + MySQL)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8%2B-4479A1?logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue)

## Sumário
- [Sobre o Projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Banco de Dados](#banco-de-dados)
- [Como Usar](#como-usar)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Exemplos de Uso](#exemplos-de-uso)
- [Troubleshooting](#troubleshooting)
- [Licença](#licença)

## Sobre o Projeto

Sistema desenvolvido para consulta e visualização de cadastros completos de pessoas físicas e jurídicas. Através de uma interface simples e intuitiva, é possível buscar informações detalhadas utilizando CPF ou CNPJ como chave de busca.

O sistema utiliza uma **Stored Procedure (sp_get_cadastro_completo_by_documento)** que retorna todos os dados relacionados ao documento informado, incluindo informações pessoais, profissionais, contatos e status de aceite dos termos.

## Funcionalidades

- ✅ Busca de cadastros por CPF (11 dígitos) ou CNPJ (14 dígitos)
- ✅ Validação automática de formatação do documento
- ✅ Exibição completa dos dados em layout responsivo com grid
- ✅ Identificação visual do tipo de cadastro (PF/PJ)
- ✅ Destaque especial para status de aceite dos termos
- ✅ Prevenção contra SQL Injection (uso de PDO)
- ✅ Ordenação automática mostrando sempre o registro mais recente
- ✅ Interface amigável com feedback visual para todas as ações

## Pré-requisitos

- PHP 8.0 ou superior
- MySQL 8.0 ou superior
- Servidor web (Apache, Nginx, ou PHP built-in server)
- Extensões PHP: PDO, PDO_MySQL

## Instalação

```bash
# Clone o repositório
git clone https://github.com/DaviDetroit/consulta-cadastros.git

# Acesse o diretório do projeto
cd consulta-cadastros


-- Exemplo da estrutura esperada (adaptar conforme seu banco real)

CREATE DATABASE IF NOT EXISTS escola;

-- A estrutura completa depende da sua procedure sp_get_cadastro_completo_by_documento
-- As tabelas envolvidas devem conter campos como:
-- - Dados pessoais (nome, cpf, nome_mae, nome_pai, nome_responsavel)
-- - Dados profissionais (razao_social, cnpj, alvara_pj)
-- - Contatos (email_pessoal, email_profissional, telefones)
-- - Registros (aceite_filiacao, registro_pf, registro_responsavel)
-- - Controle (submission_id, submitted_at, tipo_cadastro)


DELIMITER $$
CREATE PROCEDURE sp_get_cadastro_completo_by_documento(
    IN p_documento VARCHAR(20)
)
BEGIN
    -- Sua lógica de consulta aqui
    -- Deve retornar todos os campos necessários para exibição
END$$
DELIMITER ;

Configure o arquivo config.php com suas credenciais do banco:

<?php
$host = 'localhost';
$dbname = 'escola';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>

Acesse o sistema pelo navegador:
http://localhost/caminho-para-o-projeto/

Estrutura do Projeto
📁 consulta-cadastros/
│
├── 📄 index.php                    # Página principal de busca e exibição
├── 📄 config.php                    # Configuração da conexão com banco
├── 📄 README.md                      # Documentação do projeto
├── 📄 buscarcpf.php


Busca por CPF
CPF: 123.456.789-00

Busca por CNPJ
CNPJ: 12.345.678/0001-90

Resultados Exibidos
A consulta retorna um grid organizado com:

Coluna 1	Coluna 2	Coluna 3	Coluna 4
Nome completo	Razão Social (PJ)	Email pessoal	Aceite dos termos
CPF	CNPJ	Email profissional	Registro PF
Nome da mãe	Alvará	Telefone celular	Registro responsável
Nome do pai		Telefone comercial	
Responsável		Telefone fixo	
Telefone responsável	
Destaques Especiais
✅ CONCORDOU: Fundo verde indicando que a pessoa aceitou os termos

❌ NÃO CONCORDOU: Fundo vermelho com alerta especial

Tags coloridas: PF (azul), PJ (roxo)

Troubleshooting
Problemas Comuns e Soluções
"Documento inválido!"

Verifique se digitou 11 números para CPF ou 14 para CNPJ

Apenas números são considerados (formatação é ignorada)

"Nenhum cadastro encontrado"

Confirme se o documento existe no banco

Verifique se a stored procedure está retornando dados

Erro na stored procedure

Teste a procedure manualmente no MySQL:

sql
CALL sp_get_cadastro_completo_by_documento('12345678900');
Página em branco

Habilite exibição de erros no PHP

Verifique o log de erros do servidor

Confirme se a extensão PDO_MySQL está habilitada

Dados não aparecem completos

Verifique se a stored procedure retorna todos os campos esperados

Alguns campos podem ser NULL no banco


💻 Projeto desenvolvido como parte dos estudos em PHP e MySQL, focando em:

Consultas otimizadas com Stored Procedures

Prevenção contra SQL Injection

Interface responsiva e amigável

Boas práticas de programação