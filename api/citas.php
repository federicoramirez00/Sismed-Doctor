<?php
require_once('../www/php/db.php');
require_once('../www/php/validator.php');
require_once('../models/citas.php');

if (isset($_GET['action'])) {
	session_start();
	$cita = new Citas;
	$result = array('status' => 0, 'message' => null, 'exception' => null);
	if (isset($_SESSION['idUsuario']) || true) {
		switch ($_GET['action']) {
			case 'read':
				if ($result['dataset'] = $cita->readEspecialidad()) {
					$result['status'] = 1;
				} else {
					$result['exception'] = 'No hay especialidades registradas';
				}
                break;
            case 'readRealizadas':
                if ($result['dataset'] = $cita->readCitasRealizadas()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No se han realizado citas.';
                }
			case 'search':
				$_POST = $cita->validateForm($_POST);
				if ($_POST['busqueda'] != '') {
					if ($result['dataset'] = $cita->searchCitas($_POST['busqueda'])) {
						$result['status'] = 1;
						$rows = count($result['dataset']);
						if ($rows > 1) {
							$result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
						} else {
							$result['message'] = 'Solo existe una coincidencia';
						}
					} else {
						$result['exception'] = 'No hay coincidencias';
					}
				} else {
					$result['exception'] = 'Ingrese un valor para buscar';
				}
				break;
			case 'create':
				$_POST = $cita->validateForm($_POST);
				if ($cita->setEspecialidad($_POST['create_especialidad'])) {
					if ($cita->setDescripcion($_POST['create_descripcion'])) {
						if ($cita->createEspecialidad()) {
							$result['id'] = Database::getLastRowId();
							$result['status'] = 1;
						} else {
							$result['exception'] = 'Operación fallida';
						}
					} else {
						$result['exception'] = 'Descripción incorrecta';
					}
				} else {
					$result['exception'] = 'Nombre incorrecto';
				}
				break;
			case 'get':
				if ($cita->setId($_POST['id_especialidad'])) {
					if ($result['dataset'] = $cita->getCita()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'Especialidad inexistente';
					}
				} else {
					$result['exception'] = 'Especialidad incorrecta';
				}
				break;
			case 'update':
				$_POST = $cita->validateForm($_POST);
				if ($cita->setId($_POST['id_especialidad'])) {
					if ($cita->selectEspecialidad()) {
						if ($cita->setEspecialidad($_POST['update_nombre'])) {
							if ($cita->setDescripcion($_POST['update_descripcion'])) {
								if ($cita->updateEspecialidad()) {
									$result['status'] = 1;
									$result['message'] = 'Especialidad modificada correctamente';
								} else {
									$result['exception'] = 'Operación fallida';
								}
							} else {
								$result['exception'] = 'Descripción incorrecta. El campo posee caracteres no permitidos.';
							}
						} else {
							$result['exception'] = 'Nombre incorrecto. El campo posee caracteres no permitidos.';
						}
					} else {
						$result['exception'] = 'Especialidad inexistente';
					}
				} else {
					$result['exception'] = 'Especialidad incorrecta';
				}
                break;
            case 'reschedule':
                $_POST = $cita->validateForm($_POST);
                if ($cita->setIdCita($_POST['id_cita'])) {
                    if ($cita->getCita()) {
                        if ($cita->setFecha($_POST['update_fecha'])) {
                            if ($cita->setHora($_POST['update_hora'])) {
                                if ($cita->rescheduleCita()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Cita reprogramada correctamente';
                                } else {
                                    $result['exception'] = 'Operación fallida en la reprogramación de cita';
                                }
                            } else {
                                $result['exception'] = 'Hora incorrecta';
                            }
                        }
                    }
                }
			case 'delete':
				if ($cita->setId($_POST['id_especialidad'])) {
					if ($cita->selectEspecialidad()) {
						if ($cita->deleteEspecialidad()) {
							$result['status'] = 1;
							$result['message'] = 'Especialidad eliminada correctamente';
						} else {
							$result['exception'] = 'Operación fallida';
						}
					} else {
						$result['exception'] = 'Especialidad inexistente';
					}
				} else {
					$result['exception'] = 'Especialidad incorrecta';
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
