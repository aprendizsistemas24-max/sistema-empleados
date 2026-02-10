-- Base de datos del sistema de empleados
CREATE DATABASE IF NOT EXISTS sistema_empleados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE sistema_empleados;

-- Tabla de empleados
CREATE TABLE IF NOT EXISTS empleados (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    centro_costo VARCHAR(255) NOT NULL,
    cargo VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255) DEFAULT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cedula (cedula),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos datos de ejemplo basados en la imagen
-- Contraseña por defecto: "123456" (hasheada)
INSERT INTO empleados (cedula, nombre, centro_costo, cargo, password, estado) VALUES
('1143453570', 'DANNAY ANDREA FERNANDEZ OROZCO', 'BARRANQUILLA VENTAS', 'ADMINISTRADORA', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo'),
('53021071', 'MARIA CRISTINA MATIZ HERNANDEZ', 'GALERIAS VENTAS', 'ADMINISTRADORA', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo'),
('1087292293', 'MARIA CRISTINA SALGADO CONTRERAS', 'VALLEDUPAR VENTAS', 'ADMINISTRADORA', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo'),
('1102822478', 'ROXANA GERLEY GARRIDO ORTEGA', 'SINCELEJO VENTAS', 'ADMINISTRADORA', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo');
