<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/vehiculo_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla VEHICULO.
 */
class VehiculoData extends VehiculoHandler
{
    /*
     * Atributos adicionales.
     */
    private $data_error = null;

    /*
     * Métodos para validar y establecer los datos.
     */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_vehiculo = $value; // Cambiado a id_vehiculo
            return true;
        } else {
            $this->data_error = 'El identificador del vehículo es incorrecto';
            return false;
        }
    }

    public function setClienteId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value; // Cambiado a id_cliente
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    public function setMarca($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'La marca debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->marca = $value; // Cambiado a marca
            return true;
        } else {
            $this->data_error = 'La marca debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setModelo($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El modelo debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->modelo = $value; // Cambiado a modelo
            return true;
        } else {
            $this->data_error = 'El modelo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setAnio($value)
    {
        if (Validator::validateNaturalNumber($value) && $value >= 1886 && $value <= date("Y")) {
            $this->anio = $value; // Cambiado a anio
            return true;
        } else {
            $this->data_error = 'El año del vehículo es incorrecto';
            return false;
        }
    }

    public function setDescripcionProblema($value, $min = 2, $max = 250)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'La descripción del problema contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->descripcion_problema = $value; // Cambiado a descripcion_problema
            return true;
        } else {
            $this->data_error = 'La descripción del problema debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setEstadoProblema($value)
    {
        if (in_array($value, ['Activo', 'En proceso', 'Finalizado'])) {
            $this->estado_problema = $value; // Cambiado a estado_problema
            return true;
        } else {
            $this->data_error = 'El estado del problema es incorrecto';
            return false;
        }
    }

    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_vehiculo = $value; // Ahora debería funcionar
            return true;
        } else {
            $this->data_error = 'El identificador del vehículo es incorrecto';
            return false;
        }
    }

    /*
     * Métodos para obtener el valor de los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }
}
