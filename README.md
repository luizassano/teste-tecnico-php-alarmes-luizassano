# 📋 Sistema de Gerenciamento de Alarmes para Equipamentos  

Teste técnico para cadastro e manipulação de alarmes em equipamentos industriais.  

## 🎥 Demonstração do Sistema
[![Vídeo Demonstrativo do Sistema](https://img.youtube.com/vi/0ZYm27GEYus/0.jpg)](https://youtu.be/0ZYm27GEYus)  
*(Clique na imagem para assistir ao vídeo completo)*

## 🚀 Funcionalidades Principais  

### 🔧 Equipamentos  
- **Cadastro completo (CRUD)** de equipamentos com:  
  - Nome do equipamento  
  - Número de série  
  - Tipo (Tensão, Corrente ou Óleo)  
  - Data de cadastro  

### 🚨 Alarmes  
- **Gerenciamento completo (CRUD)** de alarmes com:  
  - Descrição detalhada  
  - Classificação (Urgente, Emergente ou Ordinário)  
  - Relacionamento com equipamentos  
  - Data de cadastro  

### 👨‍💻 Interface Intuitiva  
- **Ativação/Desativação inteligente** de alarmes:  
  - Bloqueio para evitar ações redundantes (ex: ativar um alarme já ativo)  

- **Painel de alarmes atuados** com:  
  - Histórico completo (entrada, saída, status)  
  - Filtros por descrição  
  - Ordenação por coluna  
  - Destaque para os **3 alarmes mais frequentes**  

## 📦 Como Configurar o Projeto  

### Pré-requisitos  
- PHP 7.4+  
- MySQL 5.7+  
- Servidor web (Apache/Nginx ou XAMPP/WAMP)  

### Passo a Passo  

1. **Configurar Banco de Dados**  
   Execute o seguinte comando no terminal para criar e popular o banco:  
   ```bash
   mysql -u root -p alarm_system < database.sql
   ```

2. **Configurar Aplicação**  
   - Renomeie `config.example.php` para `config.php`  
   - Edite com suas credenciais:  
     ```php
     define('DB_HOST', 'localhost'); 
     define('DB_NAME', 'alarm_system');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('BASE_URL', '/alarm-system/public');
     ```  

3. **Acessar o Sistema**  
   - Via terminal:  
     ```bash
     php -S localhost:8000 -t public/
     ```
   - Ou configure seu servidor web para apontar para a pasta `public/`  

## 🗃️ Sobre o Banco de Dados
O arquivo `database.sql` inclui:
- Estrutura completa das tabelas
- Dados iniciais para testes
- Relacionamentos configurados
- 3 equipamentos e 3 alarmes de exemplo

## 🔍 Primeiro Acesso
O sistema estará pronto para uso imediato após a importação do banco de dados.