<?php
class Disponibilidad extends Validator
{
    private $id = null;
    private $dia = null;
    private $horainicio = null;
    private $horafin = null;
    private $doctor = null;

    public function setId($value)
	{
		if ($this->validateId($value)) {
			$this->id = $value; 
			return true;
		} else {
			return false;
		}
	}

	public function getId()
	{
		return $this->id;
    }

    public function setDia($value)
	{
		if ($this->validateAlphabetic($value, 1, 10)) {
			$this->dia = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getDia()
	{
		return $this->dia;
    }
    
    public function setHoraInicio($value)
    {
        if ($this->validateHour($value)) {
			$this->horainicio = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getHoraInicio()
	{
		return $this->horainicio;
    }

    public function setHoraFin($value)
    {
        if ($this->validateHour($value)) {
			$this->horafin = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getHoraFin()
	{
		return $this->horafin;
    }

    public function setDoctor($value)
	{
		if ($this->validateId($value)) {
			$this->doctor = $value; 
			return true;
		} else {
			return false;
		}
	}

	public function getDoctor()
	{
		return $this->doctor;
    }

    //Métodos para manejar el CRUD
	public function readDisponibilidad()
	{
		$sql = 'SELECT t.id_disponibilidad, d.dia, t.hora_inicio, t.hora_fin, t.id_doctor FROM disponibilidad t INNER JOIN dias_disponibilidad d ON t.id_dia = d.id_dia WHERE id_doctor = ? ORDER BY d.id_dia, hora_inicio ASC';
		$params = array(4);
		return Database::getRows($sql, $params);
	}

	public function searchDisponibilidad($value)
	{
		$sql = 'SELECT id_cita, p.foto_paciente, p.nombre_paciente, p.apellido_paciente, fecha_cita, hora_cita, c.id_estado, e.estado FROM cita c INNER JOIN pacientes p ON p.id_paciente = c.id_paciente INNER JOIN estado_cita e ON e.id_estado = c.id_estado ORDER BY fecha_cita DESC';
		$params = array("%$value%", "%$value%");
		return Database::getRows($sql, $params);
	}

	public function createDisponibilidad()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO disponibilidad(nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, contrasena_doctor, fecha_nacimiento, foto_doctor,id_estado,id_especialidad) VALUES(?,?,?, ?, ?, ?, ?, ?, ?)';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $hash, $this->fecha, $this->foto,$this->idestado,$this->idespecialidad);
		return Database::executeRow($sql, $params);
	}

	public function getDisponibilidad()
	{
		$sql = 'SELECT c.id_cita, p.nombre_paciente, p.apellido_paciente, fecha_cita, hora_cita, c.id_estado FROM cita c INNER JOIN pacientes p ON p.id_paciente = c.id_paciente WHERE c.id_estado = 2 ORDER BY fecha_cita DESC';
		$params = array($this->iddoctor);
		return Database::getRow($sql, $params);
	}

	public function updateDisponibilidad()
	{
		$sql = 'UPDATE doctores SET nombre_doctor = ?, apellido_doctor = ?, correo_doctor = ?, usuario_doctor = ?, fecha_nacimiento = ?, foto_doctor = ?, id_estado = ?,id_especialidad = ? WHERE id_doctor = ?';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $this->fecha, $this->foto,$this->idestado,$this->idespecialidad, $this->iddoctor);
		return Database::executeRow($sql, $params);
	}

	public function deleteDisponibilidad()
	{
		$sql = 'DELETE FROM doctores WHERE id_doctor = ?';
		$params = array($this->iddoctor);
		return Database::executeRow($sql, $params);
    }
}