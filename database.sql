-- Criação do banco de dados
DROP DATABASE IF EXISTS `alarm_system`;
CREATE DATABASE `alarm_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `alarm_system`;

-- =============================================
-- TABELA: equipment (Equipamentos)
-- =============================================
CREATE TABLE `equipment` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do equipamento',
  `serial_number` VARCHAR(50) UNIQUE NOT NULL COMMENT 'Número de série único',
  `type` ENUM('Tensão', 'Corrente', 'Óleo') NOT NULL COMMENT 'Tipo do equipamento',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de cadastro',
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data de atualização'
) ENGINE=InnoDB;

-- =============================================
-- TABELA: alarms (Alarmes)
-- =============================================
CREATE TABLE `alarms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `description` VARCHAR(255) NOT NULL COMMENT 'Descrição do alarme',
  `classification` ENUM('Urgente', 'Emergente', 'Ordinário') NOT NULL COMMENT 'Classificação de prioridade',
  `equipment_id` INT NOT NULL COMMENT 'Equipamento relacionado',
  `status` ENUM('active', 'inactive') DEFAULT 'inactive' COMMENT 'Status atual',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de cadastro',
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data de atualização',
  FOREIGN KEY (`equipment_id`) REFERENCES `equipment`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- TABELA: alarm_activity (Histórico de Atividades)
-- =============================================
CREATE TABLE `alarm_activity` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `alarm_id` INT NOT NULL COMMENT 'Referência ao alarme',
  `status` ENUM('triggered', 'resolved') NOT NULL COMMENT 'Tipo de atividade',
  `started_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data/hora de início',
  `ended_at` DATETIME NULL COMMENT 'Data/hora de término (se resolvido)',
  FOREIGN KEY (`alarm_id`) REFERENCES `alarms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- TABELA: system_logs (Registro de atividades)
-- =============================================
CREATE TABLE `system_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `action` VARCHAR(50) NOT NULL COMMENT 'Tipo de ação',
  `description` TEXT NOT NULL COMMENT 'Descrição detalhada',
  `user` VARCHAR(50) DEFAULT 'system' COMMENT 'Usuário responsável',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de registro'
) ENGINE=InnoDB;

-- =============================================
-- ÍNDICES PARA MELHOR PERFORMANCE
-- =============================================
CREATE INDEX idx_alarms_equipment ON `alarms`(`equipment_id`);
CREATE INDEX idx_alarms_status ON `alarms`(`status`);
CREATE INDEX idx_activity_alarm ON `alarm_activity`(`alarm_id`);
CREATE INDEX idx_activity_dates ON `alarm_activity`(`started_at`, `ended_at`);

-- =============================================
-- DADOS INICIAIS PARA TESTES
-- =============================================

-- Equipamentos de exemplo
INSERT INTO `equipment` (`name`, `serial_number`, `type`) VALUES
('Transformador Principal', 'TRF-2023-001', 'Tensão'),
('Gerador de Reserva', 'GRD-2023-002', 'Corrente'),
('Tanque de Óleo 1', 'TQO-2023-003', 'Óleo'),
('Subestação A', 'SUB-2023-004', 'Tensão'),
('Painel Elétrico B', 'PEL-2023-005', 'Corrente');

-- Alarmes de exemplo
INSERT INTO `alarms` (`description`, `classification`, `equipment_id`, `status`) VALUES
('Tensão acima do limite permitido', 'Urgente', 1, 'inactive'),
('Tensão abaixo do limite mínimo', 'Emergente', 1, 'active'),
('Superaquecimento do óleo', 'Urgente', 3, 'inactive'),
('Corrente de fuga detectada', 'Ordinário', 2, 'active'),
('Vibração excessiva', 'Emergente', 4, 'inactive'),
('Temperatura elevada no transformador', 'Urgente', 1, 'inactive');

-- Histórico de ativações
INSERT INTO `alarm_activity` (`alarm_id`, `status`, `started_at`, `ended_at`) VALUES
(2, 'triggered', '2023-11-01 08:30:00', '2023-11-01 09:15:00'),
(2, 'triggered', '2023-11-02 14:20:00', NULL),
(4, 'triggered', '2023-11-03 10:45:00', '2023-11-03 11:30:00'),
(2, 'triggered', '2023-11-04 16:10:00', '2023-11-04 16:45:00'),
(4, 'triggered', '2023-11-05 09:00:00', '2023-11-05 09:30:00'),
(4, 'triggered', '2023-11-06 13:15:00', NULL);
