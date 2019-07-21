<?php
class Citas extends Validator
{
    private $idcita = null;
    private $iddoctor = null;
    private $idpaciente = null;
    private $fecha = null;
    private $hora = null;
    private $idestado = null;
    private $nombrep = null;
    private $apellidop = null;
    private $edadp = null;
    private $calificacionp = null;
    private $foto = null;
    private $ruta = '../../www/img'

    public function setIdCita($value)
	{
		if ($this->validateId($value)) {
			$this->idcita = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getIdCita()
	{
		return $this->idcita;
    }
    
    public function setIdDoctor($value)
	{
		if ($this->validateId($value)) {
			$this->iddoctor = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getIdDoctor()
	{
		return $this->iddoctor;
    }
    
    public function setIdPaciente($value)
	{
		if ($this->validateId($value)) {
			$this->idpaciente = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getIdPaciente()
	{
		return $this->idpaciente;
	}

	public function setNombre($value)
	{
		if ($this->validateAlphabetic($value, 1, 25)) {
			$this->nombrep = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getNombre()
	{
		return $this->nombrep;
	}

	public function setApellido($value)
	{
		if ($this->validateAlphabetic($value, 1, 25)) {
			$this->apellidop = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getApellido()
	{
		return $this->apellidop;
	}
    
    public function setFecha($value)
	{
		if ($this->validateDate($value)) {
			$this->fecha = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getFecha()
	{
		return $this->fecha;
    }
    
    public function setHora($value)
    {
        if ($this->validateHour($value)) {
			$this->hora = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getHora()
	{
		return $this->hora;
    }

    public function setIdEstado($value)
	{
		if ($this->validateId($value)) {
			$this->idestado = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getIdEstado()
	{
		return $this->idestado;
    }
    
    public function setEdad($value)
    {
        if ($this->validateAge($value)) {
			$this->edadp = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getEdad()
    {
        return $this->edadp;
    }

    public function setCalificacion($value)
    {
        if ($this->validateRating($value)) {
            $this->calificacionp = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getCalificacion($value)
    {
        if ($this->validateRating($value)) {
            $this->calificacionp = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setFoto($file, $name)
	{
		if ($this->validateImageFile($file, $this->ruta, $name, 500, 500)) {
			$this->foto = $this->getImageName();
			return true;
		} else {
			return false;
		}
	}

	public function getFoto()
	{
		return $this->foto;
	}

	public function getRuta()
	{
		return $this->ruta;
    }
    
    //Métodos para manejar el CRUD
	public function readDoctores()
	{
		$sql = 'SELECT id_doctor, nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, fecha_nacimiento, foto_doctor, es.id_estado, e.id_especialidad, e.nombre_especialidad, es.estado FROM doctores d INNER JOIN especialidad e ON e.id_especialidad = d.id_especialidad INNER JOIN estado_usuarios es ON es.id_estado = d.id_estado  ORDER BY apellido_doctor';
		$params = array(null);
		return Database::getRows($sql, $params);
	}

	public function searchCitas($value)
	{
		$sql = 'SELECT id_doctor, nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor FROM doctores WHERE apellido_doctor LIKE ? OR nombre_doctor LIKE ? ORDER BY apellido_doctor';
		$params = array("%$value%", "%$value%");
		return Database::getRows($sql, $params);
	}

	public function createCita()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO doctores(nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, contrasena_doctor, fecha_nacimiento, foto_doctor,id_estado,id_especialidad) VALUES(?,?,?, ?, ?, ?, ?, ?, ?)';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $hash, $this->fecha, $this->foto,$this->idestado,$this->idespecialidad);
		return Database::executeRow($sql, $params);
	}

	public function getCita()
	{
		$sql = 'SELECT id_doctor, nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, contrasena_doctor, fecha_nacimiento, foto_doctor, id_estado, id_especialidad FROM doctores WHERE id_doctor = ?';
		$params = array($this->iddoctor);
		return Database::getRow($sql, $params);
	}

	public function updateCita()
	{
		$sql = 'UPDATE doctores SET nombre_doctor = ?, apellido_doctor = ?, correo_doctor = ?, usuario_doctor = ?, fecha_nacimiento = ?, foto_doctor = ?, id_estado = ?,id_especialidad = ? WHERE id_doctor = ?';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $this->fecha, $this->foto,$this->idestado,$this->idespecialidad, $this->iddoctor);
		return Database::executeRow($sql, $params);
	}

	public function deleteDoctor()
	{
		$sql = 'DELETE FROM doctores WHERE id_doctor = ?';
		$params = array($this->iddoctor);
		return Database::executeRow($sql, $params);
    }
    
	public function cancelCita()
	{
		$sql = 'UPDATE cita SET id_estado = ? WHERE id_cita = ?';
        $params = array(3, $this->idcita);
        return Database::executeRow($sql, $params);
    }
    
    public function rescheduleCita()   
    {
        $sql = 'UPDATE cita SET fecha_cita = ?, hora_cita = ? WHERE id_cita = ?';
        $params = array($this->fecha, $this->hora, $this->idcita);
        return Database::executeRow($sql, $params);
    }

    public function readCitasRealizadas()
	{
		$sql = 'SELECT id_cita, p.nombre_paciente, p.apellido_paciente, fecha_cita, hora_cita FROM cita c INNER JOIN pacientes p ON c.id_paciente = p.id_paciente WHERE id_doctor = ? AND c.id_estado = 4 ORDER BY fecha_cita DESC';
		$params = array($this->iddoctor);
		return Database::getRow($sql, $params);
	}
}