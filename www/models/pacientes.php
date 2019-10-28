<?php
class Pacientes extends Validator
{
    private $idpaciente = null;
    private $nombre = null;
    private $apellido = null;
    private $correo = null;
    private $usuario = null;
    private $clave = null;
    private $fecha = null;
    private $foto = null;
    private $peso = null;
	private $estatura = null;
	private $telefono = null;
    private $idestado = null;
	private $ruta = null;
	private $iddoctor = null;

    public function setId($value)
	{
		if ($this->validateId($value)) {
			$this->idpaciente = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getId()
	{
		return $this->idpaciente;
	}

	public function setNombre($value)
	{
		if ($this->validateAlphabetic($value, 1, 25)) {
			$this->nombre = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function setApellido($value)
	{
		if ($this->validateAlphabetic($value, 1, 25)) {
			$this->apellido = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getApellido()
	{
		return $this->apellido;
	}

	public function setCorreo($value)
	{
		if ($this->validateEmail($value)) {
			$this->correo = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getCorreo()
	{
		return $this->correo;
	}

	public function setUsuario($value)
	{
		if ($this->validateAlphanumeric($value, 1, 25)) {
			$this->usuario = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getUsuario()
	{
		return $this->usuario;
	}

	public function setClave($value)
	{
		if ($this->validatePassword($value)) {
			$this->clave = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getClave()
	{
		return $this->clave;
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

	public function setPeso($value)
	{
		if ($this->validateWeight($value)) {
			$this->peso = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getPeso()
	{
		return $this->peso;
	}

	public function setEstatura($value)
	{
		if ($this->validateHeight($value)) {
			$this->estatura = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getEstatura()
	{
		return $this->estatura; 
	}

	public function setTelefono($value)
	{
		if ($this->validatePhoneNumber($value)) {
			$this->telefono = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getTelefono()
	{
		return $this->telefono;
	}

	public function setEstado($value)
	{
		if ($this->validateId($value)) {
			$this->idestado = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getEstado()
	{
		return $this->idestado;
	}

	public function getRuta()
	{
		return $this->ruta;
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
    
    //Manejo del CRUD
    public function readPacientes()
	{
		$sql = 'SELECT id_paciente, nombre_paciente, apellido_paciente, correo_paciente, usuario_paciente, fecha_nacimiento, foto_paciente, peso_paciente, estatura_paciente, telefono_paciente, id_estado FROM pacientes ORDER BY apellido_paciente';
		$params = array(null);
		return Database::getRows($sql, $params);
	}

	public function readPacientesByDoctor()
	{
		$sql  = 'SELECT p.id_paciente, CONCAT(p.nombre_paciente,\' \', p.apellido_paciente) AS Nombre, MAX(fecha_cita) AS UltimaCita, p.fecha_nacimiento, p.peso_paciente, p.estatura_paciente, p.telefono_paciente FROM cita c INNER JOIN pacientes p USING(id_paciente) WHERE c.id_doctor = ? AND c.id_estado = ? GROUP BY p.id_paciente';
		$params = array($this->iddoctor, 1);
		return Database::getRows($sql, $params);
	}

	public function createPaciente()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO pacientes(nombre_paciente, apellido_paciente, correo_paciente, usuario_paciente, contrasena_paciente, fecha_nacimiento, foto_paciente, peso_paciente, estatura_paciente, id_estado) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $hash, $this->fecha, $this->foto, $this->peso, $this->estatura, $this->idestado);
		return Database::executeRow($sql, $params);
	}

	public function createPacienteApp()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO pacientes(nombre_paciente, apellido_paciente, correo_paciente, fecha_nacimiento, peso_paciente, estatura_paciente) VALUES(?, ?, ?, ?, ?, ?)';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->fecha, $this->peso, $this->estatura);
		return Database::executeRow($sql, $params);
	}

	public function getPaciente()
	{
		$sql = 'SELECT id_paciente, nombre_paciente, apellido_paciente, correo_paciente, usuario_paciente, contrasena_paciente, fecha_nacimiento, foto_paciente, peso_paciente, estatura_paciente FROM pacientes WHERE id_paciente = ?';
		$params = array($this->idpaciente);
		return Database::getRow($sql, $params);
	}

	public function deletePaciente()
	{
		$sql = 'DELETE FROM pacientes WHERE id_paciente = ?';
		$params = array($this->idpaciente);
		return Database::executeRow($sql, $params);
	}
}