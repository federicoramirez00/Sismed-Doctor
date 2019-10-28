<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/pacientes.php');

//Se comprueba si existe una acción a realizar, de lo contrario se muestra un mensaje de error
if (isset($_GET['action'])) {
    session_start();
    $paciente = new Pacientes;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Se verifica si existe una sesión iniciada como administrador para realizar las operaciones correspondientes
    if (isset($_GET['idDoctor']) || true) {
        switch ($_GET['action']) {
            case 'logout':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesion cerrada correctamente';
                } else {
                    $result['status'] = 0;
                    $result['exception'] = 'Problema al cerrar la sesion';
                }
                break;
            case 'read':
                if ($result['dataset'] = $paciente->readPacientes()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay usuarios registrados';
                }
                break;
            case 'readByDoctor':
                if ($paciente->setIdDoctor($_GET['idDoctor'])) {
                    if ($result['dataset'] = $paciente->readPacientesByDoctor()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No posees pacientes antendidos previamente';
                    }
                } else {
                    $result['exception'] = 'No se definió el doctor correctamente';
                }
                break;
            case 'createApp':
                $_POST = $paciente->validateForm($_POST);
                if ($paciente->setNombre($_POST['create_nombre'])) {
                    if ($paciente->setApellido($_POST['create_apellido'])) {
                        if ($paciente->setCorreo($_POST['create_correo'])) {
                            if ($paciente->setFecha($_POST['create_fecha'])) {
                                if ($paciente->setPeso($_POST['create_peso'])) {
                                    if ($paciente->setEstatura($_POST['create_estatura'])) {
                                        if ($paciente->setTelefono($_POST['create_telefono'])) {
                                            if ($paciente->createPacienteApp()) {
                                                $result['status'] = 1;
                                            } else {
                                                $result['exception'] = 'Algo salió mal. No se pudo crear el paciente';
                                            }
                                        } else {
                                            $result['exception'] = 'Teléfono incorrecto';
                                        }
                                    } else {
                                        $result['exception'] = 'Estatura no válida';
                                    }
                                } else {
                                    $result['exception'] = 'Peso no válida';
                                }
                            } else {
                                $result['exception'] = 'Fecha de nacimiento no válida';
                            }
                        } else {
                            $result['exception'] = 'Correo electrónico no válido';
                        }
                    } else {
                        $result['exception'] = 'Apellidos no válidos';
                    }
                } else {
                    $result['exception'] = 'Nombres no válidos';
                }
                break;
            case 'create':
                $_POST = $paciente->validateForm($_POST);
                if ($paciente->setNombres($_POST['create_nombres'])) {
                    if ($paciente->setApellidos($_POST['create_apellidos'])) {
                        if ($paciente->setCorreo($_POST['create_correo'])) {
                            if ($paciente->setUsuario($_POST['create_alias'])) {
                                if ($_POST['create_clave1'] == $_POST['create_clave2']) {
                                    if ($paciente->setClave($_POST['create_clave1'])) {
                                        if ($paciente->setFecha($_POST['create_fecha'])) {
                                            if (is_uploaded_file($_FILES['create_archivo']['tmp_name'])) {
                                                if ($paciente->setFoto($_FILES['create_archivo'], null)) {
                                                    if ($paciente->createUsuario()) {
                                                        $result['status'] = 1;
                                                        if ($paciente->saveFile($_FILES['create_archivo'], $paciente->getRuta(), $paciente->getFoto())) {
                                                            $result['message'] = 'Usuario creado correctamente';
                                                        } else {
                                                            $result['message'] = 'Usuario no creado. No se guardó el archivo';
                                                        }
                                                    } else {
                                                        $result['exception'] = 'Operación fallida';
                                                    }
                                                } else {
                                                    $result['exception'] = $paciente->getImageError();
                                                }
                                            } else {
                                                $result['exception'] = 'Seleccione una imagen';
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
            case 'get':
                if ($paciente->setId($_POST['id_usuario'])) {
                    if ($result['dataset'] = $paciente->getUser()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            case 'update':
                print_r($_POST);
                $_POST = $paciente->validateForm($_POST);
                if ($paciente->setId($_POST['id_usuario'])) {
                    if ($paciente->getUser()) {
                        if ($paciente->setNombre($_POST['update_nombre'])) {
                            if ($paciente->setApellido($_POST['update_apellido'])) {
                                if ($paciente->setCorreo($_POST['update_correo'])) {
                                    if ($paciente->setUsuario($_POST['update_usuario'])) {
                                        if ($paciente->setFecha($_POST['update_fecha'])) {
                                            if ($paciente->setEstado(isset($_POST['update_estado']) ? 1 : 2)) {
                                                if (is_uploaded_file($_FILES['update_archivo']['tmp_name'])) {
                                                    if ($paciente->setFoto($_FILES['update_archivo'], $_POST['foto_usuario'])) {
                                                        $archivo = true;
                                                    } else {
                                                        $result['exception'] = $producto->getImageError();
                                                        $archivo = false;
                                                    }
                                                } else {
                                                    if (!$paciente->setFoto(null, $_POST['foto_usuario'])) {
                                                        $result['exception'] = $paciente->getImageError();
                                                    }
                                                    $archivo = false;
                                                }
                                                if ($paciente->updateUsuario()) {
                                                    $result['status'] = 1;
                                                    if ($archivo) {
                                                        if ($paciente->saveFile($_FILES['update_archivo'], $paciente->getRuta(), $paciente->getFoto())) {
                                                            $result['message'] = 'Usuario modificado correctamente.';
                                                        } else {
                                                            $result['message'] = 'Usuario modificado. No se guardó el archivo';
                                                        }
                                                    } else {
                                                        $result['message'] = 'Usuario modificado. No se subió ningún archivo';
                                                    }
                                                } else {
                                                    $result['exception'] = 'Operación fallida';
                                                }
                                            } else {
                                                $result['exception'] = 'Error con el estado';
                                            }
                                        } else {
                                            $result['exception'] = 'Fecha no válida';
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
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            case 'delete':
                if ($_POST['id_usuario'] != $_SESSION['idUsuario']) {
                    if ($paciente->setId($_POST['id_usuario'])) {
                        if ($paciente->getUser()) {
                            if ($paciente->deleteUsuario()) {
                                $result['status'] = 1;
                            } else {
                                $result['exception'] = 'Operación fallida';
                            }
                        } else {
                            $result['exception'] = 'Usuario inexistente';
                        }
                    } else {
                        $result['exception'] = 'Usuario incorrecto';
                    }
                } else {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                }
                break;
            default:
                exit('Acción no disponible 1');
        }
    } else {
        switch ($_GET['action']) {
            case 'read':
                if ($paciente->readPacientes()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un usuario registrado';
                    $result['exception'] = 'Hola';
                } else {
                    $result['message'] = 'No existen usuarios registrados';
                    $result['exception'] = 'Hola';
                }
                break;
            case 'register':
                $_POST = $paciente->validateForm($_POST);
                if ($paciente->setNombre($_POST['nombres'])) {
                    if ($paciente->setApellido($_POST['apellidos'])) {
                        if ($paciente->setCorreo($_POST['correo'])) {
                            if ($paciente->setUsuario($_POST['usuario'])) {
                                if ($_POST['clave1'] == $_POST['clave2']) {
                                    if ($paciente->setClave($_POST['clave1'])) {
                                        if ($paciente->setFecha($_POST['fecha'])) {
                                            if ($paciente->setEstado($_POST['create_estado']) ? 1 : 2) {
                                                if ($paciente->createUsuario()) {
                                                    $result['status'] = 1;
                                                } else {
                                                    $result['exception'] = 'Operación fallida';
                                                }
                                            } else {
                                                $result['exception'] = 'Estado incorrecto';
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
                $_POST = $paciente->validateForm($_POST);
                if ($paciente->setUsuario($_POST['usuario'])) {
                    if ($paciente->checkPaciente()) {
                        if ($paciente->setClave($_POST['clave'])) {
                            if ($paciente->checkPassword()) {
                                $result['id'] = $paciente->getId();
                                $result['status'] = 1;
                                $result['message'] = 'Inicio de sesión correcto';
                            } else {
                                $result['exception'] = 'Contraseña inexistente';
                            }
                        } else {
                            $result['exception'] = 'Contraseña menor a 6 caracteres';
                        }
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            default:
                exit('Acción no disponible 2');
        }
    }

    print(json_encode($result));
} else {
    exit('Recurso denegado');
}
