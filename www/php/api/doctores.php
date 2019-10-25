<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/doctores.php');

//Se comprueba si existe una acción a realizar, de lo contrario se muestra un mensaje de error
if (isset($_GET['action'])) {
    session_start();
    $doctor = new Doctores;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Se verifica si existe una sesión iniciada como administrador para realizar las operaciones correspondientes
    if (isset($_SESSION['idDoctor']) || true) {
        switch ($_GET['action']) {
            case 'logout':
                if (session_destroy()) {
                    header('location: ../../www/index.html');
                } else {
                    header('location: ../../www/dashboard.html');
                }
                break;
            case 'readProfile':
                if ($doctor->setId($_GET['idDoctor'])) {
                    if ($result['dataset'] = $doctor->getDoctor()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'Doctor inexistente';
                    }
                } else {
                    $result['exception'] = 'Doctor no válido';
                }
                break;
                case 'editProfile':
                if ($usuario->setId($_SESSION['idDoctor'])) {
                    if ($usuario->getUsuario()) {
                        $_POST = $usuario->validateForm($_POST);
                        if ($usuario->setNombres($_POST['profile_nombres'])) {
                            if ($usuario->setApellidos($_POST['profile_apellidos'])) {
                                if ($usuario->setCorreo($_POST['profile_correo'])) {
                                    if ($usuario->setAlias($_POST['profile_alias'])) {
                                        if ($usuario->updateUsuario()) {
                                            $_SESSION['aliasDoctor'] = $_POST['profile_alias'];
                                            $result['status'] = 1;
                                        } else {
                                            $result['exception'] = 'Operación fallida';
                                        }
                                    } else {
                                        $result['exception'] = 'Alias incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Correo incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Apellidos incorrectos';
                            }
                        } else {
                            $result['exception'] = 'Nombres incorrectos 2';
                        }
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            /*case 'read':
                if ($result['dataset'] = $doctor->readDoctores()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay Doctores registrados';
                }
                break;*/
            default:
                exit('Acción no disponible');
        }
    } else {
        switch ($_GET['action']) {
            case 'register':
                $_POST = $doctor->validateForm($_POST);
                if ($doctor->setNombre($_POST['nombres'])) {
                    if ($doctor->setApellido($_POST['apellidos'])) {
                        if ($doctor->setCorreo($_POST['correo'])) {
                            if ($doctor->setUsuario($_POST['alias'])) {
                                if ($_POST['clave1'] == $_POST['clave2']) {
                                    if ($doctor->setClave($_POST['clave1'])) {
                                        if ($doctor->setFecha($_POST['fecha'])) {
                                            if (is_uploaded_file($_FILES['create_archivo']['tmp_name'])) {
                                                if ($doctor->setFoto($_FILES['create_archivo'], null)) {
                                                    if ($doctor->createDoctor()) {
                                                        $result['status'] = 1;
                                                        if ($doctor->saveFile($_FILES['create_archivo'], $doctor->getRuta(), $doctor->getFoto())) {
                                                            $result['message'] = 'Doctor registrado correctamente';
                                                        } else {
                                                            $result['message'] = 'Doctor registrado. No se creó el archivo';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Operación fallida';
                                                    }
                                                } else {
                                                    $result['exception'] = $doctor->getImageError();
                                                }
                                            } else {
                                                $result['exception'] = 'Seleccione una imagen.';
                                            }
                                        } else {
                                            $result['exception'] = 'Fecha no válida';
                                        }
                                    } else {
                                        $result['exception'] = 'Clave menor a 6 caracteres';
                                    }
                                } else {
                                    $result['exception'] = 'Claves diferentes';
                                }
                            } else {
                                $result['exception'] = 'Alias incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Correo incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Apellidos incorrectos';
                    }
                } else {
                    $result['exception'] = 'Nombres incorrectos';
                }
                break;
            case 'login':
                $_POST = $doctor->validateForm($_POST);
                if ($doctor->setUsuario($_POST['usuario'])) {
                    if ($doctor->checkUser()) {
                        if ($doctor->setClave($_POST['clave'])) {
                            if ($doctor->checkPassword()) {
                                $result['id'] = $doctor->getId();
                                /*$_SESSION['idDoctor'] = $doctor->getId();
                                $_SESSION['usuarioDoctor'] = $doctor->getUsuario();*/
                                $result['status'] = 1;
                                $result['message'] = 'Inicio de sesión correcto';
                            } else {
                                $result['exception'] = 'Contraseña inexistente';
                            }
                        } else {
                            $result['exception'] = 'Contraseña menor a 6 caracteres';
                        }
                    } else {
                        $result['exception'] = 'Nombre de usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                }
                break;
            default:
                exit('Acción no disponible');
        }
    }
    print(json_encode($result));
} else {
    exit('Recurso denegado');
}
?>