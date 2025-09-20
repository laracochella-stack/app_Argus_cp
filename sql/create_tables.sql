-- SQL de creación de tablas para la plataforma Argus (versión MVC)
-- Ejecutar este script en su base de datos MySQL antes de usar la aplicación.

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS argus_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    -- Permisos disponibles: admin, moderator y user. El rol "moderator" comparte
    -- privilegios de administración de parámetros pero no puede gestionar usuarios.
    permission ENUM('admin','moderator','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS argus_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    rfc VARCHAR(13) NOT NULL,
    curp VARCHAR(20) NOT NULL,
    ine VARCHAR(50) NOT NULL,
    estado_civil VARCHAR(100) NOT NULL,
    ocupacion VARCHAR(100) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    domicilio VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    beneficiario VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de desarrollos
CREATE TABLE IF NOT EXISTS argus_desarrollos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_contrato VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    superficie VARCHAR(100) NOT NULL,
    clave_catastral VARCHAR(100) NOT NULL,
    -- Guardamos los lotes disponibles como un arreglo JSON codificado en texto. Esto permite almacenar múltiples etiquetas/lotes.
    lotes_disponibles TEXT NOT NULL,
    precio_lote DECIMAL(15,2) NOT NULL,
    precio_total DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de contratos
CREATE TABLE IF NOT EXISTS argus_contratos_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    desarrollo_id INT NOT NULL,
    datta_contrato LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES argus_clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (desarrollo_id) REFERENCES argus_desarrollos(id) ON DELETE CASCADE
);

-- Tabla de variables generales (nacionalidades, tipos de contrato, etc.)
CREATE TABLE IF NOT EXISTS argus_variables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    identificador VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de plantillas de contratos
CREATE TABLE IF NOT EXISTS argus_plantillas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_contrato_id INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_contrato_id) REFERENCES argus_variables(id) ON DELETE SET NULL
);

-- Insertar usuario administrador por defecto
-- Contraseña inicial (Admin123!) generada con password_hash
INSERT INTO argus_users (username, password, permission)
VALUES ('admin', '$2y$10$QcM8IrT9jpJ1rssZCvGSyOljNoBdscRMKx0JcCmGeX01d9K/Y/x1C', 'admin');