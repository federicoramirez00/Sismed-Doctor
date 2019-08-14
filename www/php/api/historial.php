<?php
require_once('../database.php');
require_once('../validator.php');
require_once('../models/historial.php');

//Se comprueba si existe una acción a realizar, de lo contrario se muestra un mensaje de error
if (isset($_GET['action'])) {
    session_start();
    $historial = new Historial;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Se verifica si existe una sesión iniciada como administrador para realizar las operaciones correspondientes
    if (isset($_SESSION['idUsuario']) || true) {
        switch ($_GET['action']) {
            case 'read':
                if ($result['dataset'] = $historial->readConsulta()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay consultas registradas';
                }
                break;
            case 'getByPaciente':
                if ($historial->setIdpaciente($_GET['idUsuario'])) {
                    if ($result['dataset'] = $historial->getHistorialByPaciente()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No hay citas registrados';
                    }
                } else {
                    $result['exception'] = 'Paciente incorrecto';
                }

                break;
            default:
                exit('Acción no disponible');
        }
        print(json_encode($result));
    } else {
        exit('Acceso no disponible');
    }
} else {
    exit('Recurso denegado');
}
