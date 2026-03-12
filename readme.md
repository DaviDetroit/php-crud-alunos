# 📚 Sistema de Cadastro de Alunos (PHP + MySQL)

Este é um pequeno projeto desenvolvido para praticar **desenvolvimento backend com PHP e MySQL**.  
O sistema permite cadastrar alunos e visualizar a lista de alunos cadastrados em uma página web.

## 🚀 Funcionalidades

- Cadastro de alunos (nome e email)
- Armazenamento de dados em banco **MySQL**
- Listagem dinâmica dos alunos cadastrados
- Conexão segura com o banco utilizando **PDO**
- Uso de **variáveis de ambiente (.env)** para configuração do banco

## 🛠️ Tecnologias utilizadas

- PHP
- MySQL
- HTML
- PDO (PHP Data Objects)

## 📂 Estrutura do projeto


escola/
│
├── config.php # Conexão com o banco de dados
├── cadastrar.php # Formulário para cadastrar aluno
├── salvar.php # Script responsável por salvar no banco
├── index.php # Página que lista os alunos
└── .env # Variáveis de ambiente do banco


## ⚙️ Como rodar o projeto

1. Clone o repositório


git clone https://github.com/DaviDetroit/php-crud-alunos.git


2. Crie um banco de dados no MySQL


CREATE DATABASE escola;


3. Crie a tabela de alunos


CREATE TABLE alunos (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100),
email VARCHAR(100)
);


4. Configure o arquivo `.env`


DB_HOST=localhost
DB_PORT=3306
DB_NAME=escola
DB_USER=root
DB_PASSWORD=


5. Execute o projeto no servidor local (ex: **Laragon, XAMPP ou WAMP**)


http://localhost/escola


## 🎯 Objetivo do projeto

Este projeto foi desenvolvido com foco em **aprendizado de backend com PHP**, integração com banco de dados e compreensão do funcionamento de um sistema simples de cadastro.

Futuramente o projeto pode evoluir para incluir:

- edição de alunos
- exclusão de registros
- sistema de turmas
- sistema de notas
- autenticação de usuários

## 📸 Demonstração

💻 Projeto desenvolvido como parte dos meus estudos em programação e banco de dados.