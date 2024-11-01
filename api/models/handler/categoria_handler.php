<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 * Clase para manejar el comportamiento de los datos de la tabla SERVICIO.
 */
class ServicioHandler
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $imagen = null;
    protected $precio = null; // Añadido para el precio del servicio

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/servicios/';

    /*
     * Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_servicio, nombre_servicio, imagen_servicio, descripcion_servicio
                FROM servicio
                WHERE nombre_servicio LIKE ? OR descripcion_servicio LIKE ?
                ORDER BY nombre_servicio';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO servicio(nombre_servicio, imagen_servicio, descripcion_servicio, precio_servicio)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->imagen, $this->descripcion, $this->precio);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, imagen_servicio, descripcion_servicio, precio_servicio
                FROM servicio
                ORDER BY nombre_servicio';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, imagen_servicio, descripcion_servicio, precio_servicio
                FROM servicio
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen_servicio
                FROM servicio
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE servicio
                SET imagen_servicio = ?, nombre_servicio = ?, descripcion_servicio = ?, precio_servicio = ?
                WHERE id_servicio = ?';
        $params = array($this->imagen, $this->nombre, $this->descripcion, $this->precio, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM servicio
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener el top 5 de servicios más solicitados (ajustado según la estructura de tu base de datos)
    public function readTopServicios()
    {
        $sql = 'SELECT nombre_servicio, COUNT(*) total
                FROM servicio
                INNER JOIN detalle_servicio USING(id_servicio) // Asumiendo que tienes una tabla de detalles de servicio
                WHERE id_servicio = ?
                GROUP BY nombre_servicio
                ORDER BY total DESC
                LIMIT 5';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
}
