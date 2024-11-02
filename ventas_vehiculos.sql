-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS vehicle_management DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE vehicle_management;

-- Tabla de usuarios generales
CREATE TABLE users (
  id_user INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  age INT(3) NOT NULL,
  PRIMARY KEY (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabla de clientes
CREATE TABLE clients (
  id_client INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  client_name VARCHAR(50) NOT NULL,
  client_phone VARCHAR(15) NOT NULL,
  client_address VARCHAR(250) NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_client)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabla de vehículos
CREATE TABLE vehicles (
  id_vehicle INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  client_id INT(10) UNSIGNED NOT NULL,
  brand VARCHAR(50) NOT NULL,
  model VARCHAR(50) NOT NULL,
  year INT(4) NOT NULL,
  problem_description VARCHAR(250) NOT NULL,
  problem_status ENUM('Activo', 'En proceso', 'Finalizado') NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_vehicle),
  FOREIGN KEY (client_id) REFERENCES clients(id_client) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insertar datos iniciales en la tabla clients
INSERT INTO clients (client_name, client_phone, client_address) VALUES 
('Carlos García', '555-1234', 'Av. Central 123'),
('María López', '555-5678', 'Calle Norte 456'),
('Luis Pérez', '555-8765', 'Avenida Sur 789'),
('Ana Martínez', '555-4321', 'Camino Real 101'),
('José Rodríguez', '555-1111', 'Boulevard Principal 202');

-- Insertar datos iniciales en la tabla vehicles
INSERT INTO vehicles (client_id, brand, model, year, problem_description, problem_status) VALUES 
(1, 'Toyota', 'Corolla', 2015, 'No enciende el motor', 'Activo'),
(2, 'Honda', 'Civic', 2018, 'Falla en el sistema de frenos', 'En proceso'),
(3, 'Ford', 'Focus', 2012, 'Vibración en el volante', 'Activo'),
(4, 'Chevrolet', 'Spark', 2020, 'Luces delanteras no funcionan', 'Finalizado'),
(5, 'Nissan', 'Versa', 2016, 'Problema con la transmisión', 'En proceso');

COMMIT;
