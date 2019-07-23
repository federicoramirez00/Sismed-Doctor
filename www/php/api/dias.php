<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/dias.php');

if (isset($_GET['action'])) {
    session_start();
    $dia = new Dias();
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idUsuario']) || true) {
        switch ($_GET['action']) {
            case 'read':
                if ($result['dataset'] = $dia->readDias()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay días registrados';
                }
                break;
        }
        print(json_encode($result));
    } else {
        exit('Acceso no disponible');
    }
} else {
    exit('Recurso denegado');
}
