<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/disponibilidad.php');

if (isset($_GET['action'])) {
    session_start();
    $disponibilidad = new Disponibilidad;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idUsuario']) || true) {
        switch ($_GET['action']) {
            case 'readDisponibilidad':
                if ($result['dataset'] = $disponibilidad->readDisponibilidad()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay disponibilidades registradas';
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
				if ($disponibilidad->setId($_POST['id_disponibilidad'])) {
					if ($result['dataset'] = $disponibilidad->getDisponibilidad()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'Disponibilidad inexistente';
					}
				} else {
					$result['exception'] = 'Disponibilidad incorrecta';
				}
				break;
			case 'count':
				if ($cita->setIdCita($_POST['id_cita'])) {
					if ($result['dataset'] = $cita->countCitasDiarias()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'Contenido no disponible';
					}
				} else {
					$result['exception'] = 'Cita incorrecto';
				}
				break;
			case 'update':
				$_POST = $disponibilidad->validateForm($_POST);
				if ($disponibilidad->setId($_POST['id_disponibilidad'])) {
					if ($disponibilidad->getDisponibilidad()) {
						if ($disponibilidad->setDia($_POST['readDias'])) {
							if ($disponibilidad->setHoraInicio($_POST['update_inicio'])) {
								if ($disponibilidad->setHoraFin($_POST['update_fin'])) {
									if ($disponibilidad->updateDisponibilidad()) {
										$result['status'] = 1;
										$result['message'] = 'Disponibilidad modificada correctamente';
									} else {
										$result['exception'] = 'Operación fallida. No se pudo actualizar';
									}
								} else {
									$result['exception'] = 'Hora fin incorrecta';
								}
							} else {
								$result['exception'] = 'Hora inicio incorrecta';
							}
						} else {
							$result['exception'] = 'Dia incorrecto';
						}
					} else {
						$result['exception'] = 'Disponibilidad inexistente';
					}
				} else {
					$result['exception'] = 'Disponibilidad incorrecta';
				}
				break;
			case 'reschedule':
			print_r('si entra');
				$_POST = $cita->validateForm($_POST);
				if ($cita->setIdCita($_POST['id_cita'])) {
					if ($cita->getCita()) {
						print_r('xd');
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
				break;
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
