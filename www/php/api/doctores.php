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
                if ($doctor->setId($_GET['idDoctor'])) {
                    if ($doctor->getDoctor()) {
                        $_POST = $doctor->validateForm($_POST);
                        if ($doctor->setNombre($_POST['profile_nombre'])) {
                            if ($doctor->setApellido($_POST['profile_apellido'])) {
                                if ($doctor->setCorreo($_POST['profile_correo'])) {
                                    if ($doctor->setUsuario($_POST['profile_usuario'])) {
                                        if ($doctor->setFecha($_POST['profile_fecha'])) {
                                            if ($doctor->setTelefono($_POST['profile_telefono'])) {
                                                if ($doctor->updateProfile()) {
                                                    $result['status'] = 1;
                                                } else {
                                                    $result['exception'] = 'Operación fallida';
                                                }
                                            } else {
                                                $result['exception'] = 'Teléfono incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = 'Fecha de nacimiento incorrecta';
                                        }
                                    } else {
                                        $result['exception'] = 'Nombre de usuario incorrecto';
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
                    } else {
                        $result['exception'] = 'Doctor inexistente';
                    }
                } else {
                    $result['exception'] = 'Doctor incorrecto';
                }
                break;
            case 'password':
                if ($doctor->setId($_GET['idDoctor'])) {
                    $_POST = $doctor->validateForm($_POST);
                        if ($doctor->setClave($_POST['clave_actual'])) {
                            if ($doctor->checkPassword()) {
                                if ($_POST['clave_actual'] != $_POST['clave_nueva_1']) {
                                    if ($_POST['clave_nueva_1'] == $_POST['clave_nueva_2']) {
                                        $resultado = $doctor->setClave($_POST['clave_nueva_1']);
                                        if ($resultado[0]) {
                                            if ($doctor->changePassword()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Contraseña actualizada correctamente';
                                            } else {
                                                $result['exception'] = 'Operación fallida';
                                            }
                                        } else {
                                            $result['exception'] = $resultado[1];
                                        }
                                    } else {
                                        $result['exception'] = 'Contraseñas nuevas no coinciden';
                                    }
                                } else {
                                    $result['exception'] = 'La nueva contraseña no puede ser igual a la actual';
                                }
                            } else {
                                $result['exception'] = 'Contraseña actual incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Contraseña actual menor a 8 caracteres';
                        }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
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