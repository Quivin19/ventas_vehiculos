<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
* Clase para manejar el comportamiento de los datos de la tabla VEHICULO.
*/
class VehiculoHandler
{
    /*
    * Declaración de atributos para el manejo de datos.
    */
    protected $id_vehiculo;       // Identificador del vehículo
    protected $id_cliente;         // Identificador del cliente
    protected $marca;              // Marca del vehículo
    protected $modelo;             // Modelo del vehículo
    protected $anio;               // Año del vehículo
    protected $descripcion_problema; // Descripción del problema
    protected $estado_problema;    // Estado del problema

    /*
    * Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT v.id_vehicle, c.client_name, v.marca, v.modelo, v.anio, v.descripcion_problema, v.estado_problema
                FROM vehicles v
                INNER JOIN clients c ON v.client_id = c.id_client
                WHERE v.marca LIKE ? OR v.modelo LIKE ? OR v.descripcion_problema LIKE ?
                ORDER BY v.marca';
        $params = array($value, $value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO vehicles(client_id, marca, modelo, anio, descripcion_problema, estado_problema)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->client_id, $this->marca, $this->modelo, $this->anio, $this->descripcion_problema, $this->estado_problema);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT v.id_vehicle, c.client_name, v.marca, v.modelo, v.anio, v.descripcion_problema, v.estado_problema
                FROM vehicles v
                INNER JOIN clients c ON v.client_id = c.id_client
                ORDER BY v.marca';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT v.id_vehicle, c.client_name, v.marca, v.modelo, v.anio, v.descripcion_problema, v.estado_problema
                FROM vehicles v
                INNER JOIN clients c ON v.client_id = c.id_client
                WHERE v.id_vehicle = ?';
        $params = array($this->id_vehicle);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE vehicles
                SET client_id = ?, marca = ?, modelo = ?, anio = ?, descripcion_problema = ?, estado_problema = ?
                WHERE id_vehicle = ?';
        $params = array($this->client_id, $this->marca, $this->modelo, $this->anio, $this->descripcion_problema, $this->estado_problema, $this->id_vehicle);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM vehicles
                WHERE id_vehicle = ?';
        $params = array($this->id_vehicle);
        return Database::executeRow($sql, $params);
    }
}
