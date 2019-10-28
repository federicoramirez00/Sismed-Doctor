<?php
require_once('../db.php');
require_once('../validator.php');
require_once('../models/citas.php');

if (isset($_GET['action'])) {
	session_start();
	$cita = new Citas;
	$result = array('status' => 0, 'message' => null, 'exception' => null);
	if (isset($_GET['idDoctor']) || true) {
		switch ($_GET['action']) {
			case 'readCita':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
					if ($result['dataset'] = $cita->readCitas()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'No hay citas registradas';
					}
				} else {
					$result['exception'] = 'No se encontraron datos para el doctor';
				}
				break;
			case 'readRealizadas':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
					if ($result['dataset'] = $cita->readCitasRealizadas()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'No se han realizado citas.';
					}
				} else {
					$result['exception'] = 'No se encuentran datos para el doctor';
				}
				break;
			case 'countPreCitas':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
					if ($result['dataset'] = $cita->countPreCitas()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'No hay citas.';
					}
				} else {
					$result['exception'] = 'No se encuentran datos.';
				}
				break;
			case 'readPreCitas':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
					if ($result['dataset'] = $cita->readPreCitas()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'No se han reservado citas.';
					}
				} else {
					$result['exception'] = 'No se encontró el doctor';
				}
				break;
			case 'aceptarPreCita':
				//if ($cita->setIdDoctor($_GET['idDoctor'])) {
				if ($cita->setIdestado(4)) {
					if ($cita->setIdcita($_POST['id_cita'])) {
						if ($cita->updatePreCita()) {
							$result['status'] = 1;
							$result['exception'] = 'Operación fallida';
						} else {
							$result['exception'] = 'Operación fallida';
						}
					} else {
						$result['exception'] = 'Cita incorrecta';
					}
				} else {
					$result['exception'] = 'Estado incorrecto';
				}
				/*} else {
					$result['exception'] = 'No se encuentran datos para del doctor';
				}*/
				break;
			case 'cancelarPreCita':
				//if ($cita->setIdDoctor($_GET['idDoctor'])) {
				if ($cita->setIdestado(3)) {
					if ($cita->setIdcita($_POST['id_cita'])) {
						if ($cita->updateEstado()) {
							$result['status'] = 1;
							$result['exception'] = 'Operación fallida';
						} else {
							$result['exception'] = 'Operación fallida';
						}
					} else {
						$result['exception'] = 'Cita incorrecta';
					}
				} else {
					$result['exception'] = 'Estado incorrecto';
				}
				/*} else {
					$result['exception'] = 'No se encuentran datos para el doctor';
				}*/
				break;
			case 'realizarCita':
				//if ($cita->setIdDoctor($_GET['idDoctor'])) {
				if ($cita->setIdestado(1)) {
					if ($cita->setIdcita($_POST['id_cita'])) {
						if ($cita->updateEstado()) {
							$result['status'] = 1;
							$result['exception'] = 'Operación fallida';
						} else {
							$result['exception'] = 'Operación fallida';
						}
					} else {
						$result['exception'] = 'Cita incorrecta';
					}
				} else {
					$result['exception'] = 'Estado incorrecto';
				}
				/*} else {
					$result['exception'] = 'No se encuentran datos para el doctor';
				}*/
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
				if ($cita->setIdCita($_POST['id_cita'])) {
					if ($result['dataset'] = $cita->getCita()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'Cita inexistente';
					}
				} else {
					$result['exception'] = 'Cita incorrecta';
				}
				break;
			case 'getProximaCita':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
					if ($result['dataset'] = $cita->getProximaCita()) {
						$result['status'] = 1;
					} else {
						$result['exception'] = 'Cita inexistente';
					}
				} else {
					$result['exception'] = 'No se definió el doctor';
				}
				break;
			case 'count':
				if ($cita->setIdDoctor($_GET['idDoctor'])) {
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
				$_POST = $cita->validateForm($_POST);
				if ($cita->setIdCita($_POST['id_especialidad'])) {
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
				if ($cita->setIdCita($_POST['id_cita'])) {
					if ($cita->getCita()) {
						if ($cita->deleteCita()) {
							$result['status'] = 1;
							$result['message'] = 'Cita eliminada correctamente';
						} else {
							$result['exception'] = 'Algo salió mal. No se pudo eliminar la cita';
						}
					} else {
						$result['exception'] = 'Cita inexistente';
					}
				} else {
					$result['exception'] = 'Cita incorrecta';
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
