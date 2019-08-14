<?php
require_once('../database.php');
require_once('../validator.php');
require_once('../models/especialidad.php');


//Se comprueba si existe una petición del sitio web y la acción a realizar, de lo contrario se muestra una página de error
if (isset($_GET['action'])) {
    session_start();
    $especialidad = new Especialidad;
    $result = array('status' => 0, 'exception' => '');
    //Se verifica si existe una sesión iniciada como administrador para realizar las operaciones correspondientes
        switch ($_GET['action']) {
           /* case 'readDoc':
                if ($result['dataset'] = $especialidad->readDoctoresEspecialidad()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay productos registrados';
                }
                break;*/
                case 'readDoc':
                if ($especialidad->setEspecialidad($_POST['id_especialidad'])) {
                    if ($result['dataset'] = $especialidad->readDoctoresEspecialidad()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'Contenido no disponible';
                    }
                } else {
                    $result['exception'] = 'Categoría incorrecta';
                }
                break;
            case 'readEspecialidad':
                if ($result['dataset'] = $especialidad->readEspecialidades()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay categorías registradas';
                }
                break;
          
            case 'get':
                if ($especialidad->setId($_POST['id_especialidad'])) {
                    if ($result['dataset'] = $especialidad->getDoctor()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'Producto inexistente';
                    }
                } else {
                    $result['exception'] = 'Producto incorrecto';
                }
                break;
           
            default:
                exit('Acción no disponible');
            }
            print(json_encode($result));
        } else {
            exit('Recurso denegado');
        }
?>
