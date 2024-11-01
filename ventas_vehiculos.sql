-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS vehicle_management DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE vehicle_management;

-- Estructura de tabla para la tabla users (usuarios generales)
CREATE TABLE users (
  id_user INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  age INT(3) NOT NULL, -- Añadido para almacenar la edad del usuario
  PRIMARY KEY (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla clients
CREATE TABLE clients (
  id_client INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  client_name VARCHAR(50) NOT NULL,
  client_phone VARCHAR(15) NOT NULL,
  client_address VARCHAR(250) NOT NULL, -- Puedes eliminar este campo si no es necesario
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_client)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla vehicles
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

COMMIT;
