# ☕ Sistema de Gerenciamento de Cafeteria

Este projeto tem como objetivo o desenvolvimento de uma aplicação para **gerenciar uma cafeteria**, centralizando e automatizando tarefas essenciais como cadastro de clientes, administradores, ingredientes e pedidos.  
O sistema é apoiado por um banco de dados estruturado, garantindo **segurança, integridade e agilidade** no acesso às informações.

---

## 📖 Sumário
- [Descrição](#-descrição)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Requisitos do Sistema](#-requisitos-do-sistema)
- [Funcionalidades](#-funcionalidades)
- [Modelagem do Banco de Dados](#-modelagem-do-banco-de-dados)
- [Instalação e Execução](#-instalação-e-execução)

---

## 📌 Descrição
O sistema busca resolver problemas comuns enfrentados por cafeterias, como:
- Dificuldades no controle de estoque
- Desperdício de insumos
- Falhas no atendimento  
 
Com essa solução, o estabelecimento passa a ter **visão centralizada das operações internas**, maior eficiência e redução de erros.

---

## 💻 Tecnologias Utilizadas
- **PHP**
- **MySQL**
- **HTML, CSS e JavaScript**
- **Git/GitHub**

---

## ✅ Requisitos do Sistema

### Funcionais
- **RF01 – CRUD Usuários:** cadastro, login, atualização e exclusão, com tipos de permissão.  
- **RF02 – CRUD Produto:** cadastro, leitura, atualização e exclusão (somente para funcionários e administradores).  
- **RF03 – CRUD Pedidos:** visualizar, cadastrar, atualizar e excluir pedidos (funcionários e administradores).  

### Não Funcionais
- **Desempenho:** sistema rápido.  
- **Disponibilidade:** 99% de uptime mensal.  
- **Usabilidade:** interface simples e intuitiva.  
- **Integridade dos Dados:** segurança contra perda ou corrupção.  
- **Compatibilidade:** navegadores modernos (Chrome, Edge, Firefox).  

---

## ⚙️ Funcionalidades
- Cadastro e login de clientes e administradores  
- Gerenciamento de estoque de ingredientes  
- Controle de pedidos em tempo real  
- Interface integrada para acompanhamento de operações  

---

## 🗄 Modelagem do Banco de Dados
- **DER** – Diagrama Entidade Relacionamento  
- **Modelo Lógico** – definição das tabelas  
- **Modelo Físico** – disponível no OneDrive e no github

---

## 🚀 Instalação e Execução
```bash
# Clonar o repositório
git clone https://github.com/JulisM0/Cafeteria.git

# Entrar no diretório
cd Cafeteria

# Configurar banco de dados MySQL (importar script disponível no projeto)

# Executar no servidor local (ex: XAMPP ou Laragon)
