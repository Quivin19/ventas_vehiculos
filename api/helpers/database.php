<?php
// Se incluyen las credenciales para conectar con la base de datos.
require_once('config.php');

/*
 *   Clase para realizar las operaciones en la base de datos para la aplicación de gestión de vehículos.
 */
class Database
{
    // Propiedades de la clase para manejar las acciones respectivas.
    private static $connection = null;
    private static $statement = null;
    private static $error = null;

    /*
     *   Método para ejecutar las sentencias SQL.
     *   Parámetros: $query (sentencia SQL) y $values (arreglo con los valores para la sentencia SQL).
     *   Retorno: booleano (true si la sentencia se ejecuta satisfactoriamente o false en caso contrario).
     */
    public static function executeRow($query, $values)
    {
        try {
            // Crear la conexión utilizando PDO y el controlador de MySQL.
            self::$connection = new PDO('mysql:host=' . SERVER . ';dbname=' . DATABASE, USERNAME, PASSWORD);
            // Preparar la sentencia SQL.
            self::$statement = self::$connection->prepare($query);
            // Ejecutar la sentencia preparada con los valores y retornar el resultado.
            return self::$statement->execute($values);
        } catch (PDOException $error) {
            // En caso de excepción, establecer un mensaje de error personalizado.
            self::setException($error->getCode(), $error->getMessage());
            return false;
        }
    }

    /*
     *   Método para obtener el valor de la última llave primaria insertada.
     *   Parámetros: $query (sentencia SQL) y $values (arreglo con los valores para la sentencia SQL).
     *   Retorno: último valor de la llave primaria si la sentencia se ejecuta correctamente, o 0 en caso contrario.
     */
    public static function getLastRow($query, $values)
    {
        if (self::executeRow($query, $values)) {
            $id = self::$connection->lastInsertId();
        } else {
            $id = 0;
        }
        return $id;
    }

    /*
     *   Método para obtener un solo registro de una sentencia SQL SELECT.
     *   Parámetros: $query (sentencia SQL) y $values (arreglo opcional con valores para la sentencia SQL).
     *   Retorno: un arreglo asociativo con el registro o false en caso contrario.
     */
    public static function getRow($query, $values = null)
    {
        if (self::executeRow($query, $values)) {
            return self::$statement->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /*
     *   Método para obtener todos los registros de una sentencia SQL SELECT.
     *   Parámetros: $query (sentencia SQL) y $values (arreglo opcional con valores para la sentencia SQL).
     *   Retorno: un arreglo asociativo de los registros o false en caso de error.
     */
    public static function getRows($query, $values = null)
    {
        if (self::executeRow($query, $values)) {
            return self::$statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /*
     *   Método para establecer un mensaje de error personalizado al ocurrir una excepción.
     *   Parámetros: $code (código del error) y $message (mensaje original del error).
     *   Retorno: ninguno.
     */
    private static function setException($code, $message)
    {
        // Asignar el mensaje original del error para referencia.
        self::$error = $message . PHP_EOL;
        // Manejar diferentes códigos de error con mensajes personalizados.
        switch ($code) {
            case '2002':
                self::$error = 'Error: Servidor desconocido';
                break;
            case '1049':
                self::$error = 'Error: Base de datos no encontrada';
                break;
            case '1045':
                self::$error = 'Error: Acceso denegado, credenciales incorrectas';
                break;
            case '42S02':
                self::$error = 'Error: Tabla no encontrada en la base de datos';
                break;
            case '42S22':
                self::$error = 'Error: Columna especificada no encontrada';
                break;
            case '23000':
                self::$error = 'Error: Violación de restricción de integridad';
                break;
            default:
                self::$error = 'Error: Problema desconocido en la base de datos';
        }
    }

    /*
     *   Método para obtener el mensaje de error personalizado cuando ocurre una excepción.
     *   Parámetros: ninguno.
     *   Retorno: error personalizado.
     */
    public static function getException()
    {
        return self::$error;
    }
}
