-- COMANDOS PARA CREAR LA BASE DE DATOS
-- PARA MEGAGEST Y SU INTERACCIÓN CON PHP

-- 1. Crear la base de datos 
CREATE DATABASE db_mecagest;

-- 2.Usar/Activar la base de datos
USE db_mecagest;

-- 3.Crear la tabla de usarios
CREATE TABLE  usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status TINYINT(1) DEFAULT 1
);

-- 4. VISUALIZAR LA ESTRUCTURA DE LA TABLA
DESCRIBE usuarios;

-- 5. seleccionar los registros de la tabla 
SELECT * FROM usuarios;

-- 6 . ejemplo de inserción de un nuevo usuario
INSERT INTO usuarios VALUES(default, "Jessica Gutierrez","jess@gmail.com",md5("123456"),now(),null,1);

-- 7. inserción multiple de otros dos usuarios 
INSERT INTO usuarios (nombre,email,password) values
("lili Garcia","lili@gmail.com",md5("garcia123")),
("Patricia Reyes","patty.reyes48@outlook",md5("67890"));







