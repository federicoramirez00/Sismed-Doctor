<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/consultas.php');

if (isset($_GET['action'])) {
    session_start();
    $consultas = new Consultas;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Se verifica si existe una sesión iniciada como administrador para realizar las operaciones correspondientes
    if (isset($_SESSION['idUsuario']) || true) {
        switch ($_GET['action']) {
                //casos utilizados para la realización de gráficos
                //caso para mostrar consultas totales de cada mes
            case 'consultasFecha':
                if ($result['dataset'] = $consultas->consultasPorFecha()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos por mostrar.';
                }
                break;
            case 'citasEstadoDoctor':
                if ($result['dataset'] = $consultas->showCitasEstadoDoctor()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos por mostrar';
                }
                break;
            default:
                exit('Acción no disponible');
        }
    } else {
        switch ($_GET['action']) {
                /*case 'read':
                if ($consultas->readDoctores()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un doctor registrado';
                } else {
                    $result['message'] = 'No existen doctores registrados';
                }
                break;*/
            default:
                exit('Acción no disponible');
        }
    }
    print(json_encode($result));
} else {
    exit('Recurso denegado');
}
