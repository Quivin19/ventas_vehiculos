<?php
// Se incluye la clase del modelo.
require_once('../../models/data/vehiculo_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $vehiculo = new VehiculoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $vehiculo->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$vehiculo->setMarca($_POST['marcaVehiculo']) or
                    !$vehiculo->setModelo($_POST['modeloVehiculo']) or
                    !$vehiculo->setAnio($_POST['anioVehiculo']) or
                    !$vehiculo->setPrecio($_POST['precioVehiculo']) or
                    !$vehiculo->setEstado(isset($_POST['estadoVehiculo']) ? 1 : 0) or
                    !$vehiculo->setImagen($_FILES['imagenVehiculo'])
                ) {
                    $result['error'] = $vehiculo->getDataError();
                } elseif ($vehiculo->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vehículo creado correctamente';
                    // Se asigna el estado del archivo después de insertar.
                    $result['fileStatus'] = Validator::saveFile($_FILES['imagenVehiculo'], $vehiculo::RUTA_IMAGEN);
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el vehículo';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $vehiculo->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen vehículos registrados';
                }
                break;
            case 'readOne':
                if (!$vehiculo->setId($_POST['idVehiculo'])) {
                    $result['error'] = $vehiculo->getDataError();
                } elseif ($result['dataset'] = $vehiculo->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Vehículo inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$vehiculo->setId($_POST['idVehiculo']) or
                    !$vehiculo->setFilename() or
                    !$vehiculo->setMarca($_POST['marcaVehiculo']) or
                    !$vehiculo->setModelo($_POST['modeloVehiculo']) or
                    !$vehiculo->setAnio($_POST['anioVehiculo']) or
                    !$vehiculo->setPrecio($_POST['precioVehiculo']) or
                    !$vehiculo->setEstado(isset($_POST['estadoVehiculo']) ? 1 : 0) or
                    !$vehiculo->setImagen($_FILES['imagenVehiculo'], $vehiculo->getFilename())
                ) {
                    $result['error'] = $vehiculo->getDataError();
                } elseif ($vehiculo->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vehículo modificado correctamente';
                    // Se asigna el estado del archivo después de actualizar.
                    $result['fileStatus'] = Validator::changeFile($_FILES['imagenVehiculo'], $vehiculo::RUTA_IMAGEN, $vehiculo->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el vehículo';
                }
                break;
            case 'deleteRow':
                if (
                    !$vehiculo->setId($_POST['idVehiculo']) or
                    !$vehiculo->setFilename()
                ) {
                    $result['error'] = $vehiculo->getDataError();
                } elseif ($vehiculo->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vehículo eliminado correctamente';
                    // Se asigna el estado del archivo después de eliminar.
                    $result['fileStatus'] = Validator::deleteFile($vehiculo::RUTA_IMAGEN, $vehiculo->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el vehículo';
                }
                break;
            case 'cantidadVehiculosCategoria':
                if ($result['dataset'] = $vehiculo->cantidadVehiculosCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
            case 'porcentajeVehiculosCategoria':
                if ($result['dataset'] = $vehiculo->porcentajeVehiculosCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
