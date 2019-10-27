<?php
class Doctores extends Validator
{
    private $iddoctor = null;
    private $nombre = null;
    private $apellido = null;
    private $correo = null;
    private $usuario = null;
    private $clave = null;
	private $fecha = null;
	private $telefono = null;
    private $foto = null;
    private $especialidad = null;
    private $estado = null;
    private $ruta = '../../';

    public function setId($value)
	{
		if ($this->validateId($value)) {
			$this->iddoctor = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getId()
	{
		return $this->iddoctor;
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
	
	public function setEspecialidad($value)
	{
		if ($this->validateId($value)) {
			$this->especialidad = $value;
			return true;
		} else {
			return false;
		}
	}

	public function getEspecialidad()
	{
		return $this->especialidad;
	}

	public function setEstado($value)
	{
		if ($this->validateId($value)) {
			$this->estado = $value;
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
	
	public function checkUser()
	{
		$sql = 'SELECT id_doctor FROM doctores WHERE usuario_doctor = ?';
		$params = array($this->usuario);
		$data = Database::getRow($sql, $params);
		if ($data) {
			$this->iddoctor = $data['id_doctor'];
			return true;
		} else {
			return false;
		}
	}

	public function checkPassword()
	{
		$sql = 'SELECT contrasena_doctor FROM doctores WHERE id_doctor = ?';
		$params = array($this->iddoctor);
		$data = Database::getRow($sql, $params);
		if (password_verify($this->clave, $data['contrasena_doctor'])) {
            return true;
        } else {
            return false;
        }
	}
    
    public function createDoctor()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO doctores(nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, contrasena_doctor, fecha_nacimiento, foto_doctor, id_especialidad, id_estado) VALUES(?,?,?, ?, ?, ?, ?, ?, ?)';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $hash, $this->fecha, $this->foto, $this->especialidad, $this->estado);
		return Database::executeRow($sql, $params);
	}

	public function getDoctor()
	{
		$sql = 'SELECT id_doctor, nombre_doctor, apellido_doctor, correo_doctor, usuario_doctor, contrasena_doctor, fecha_nacimiento, foto_doctor, telefono_doctor FROM doctores WHERE id_doctor = ?';
		$params = array($this->iddoctor);
		return Database::getRow($sql, $params);
	}

	public function updateDoctor()
	{
		$sql = 'UPDATE doctores SET nombre_doctor = ?, apellido_doctor = ?, correo_doctor = ?, usuario_doctor = ?, fecha_nacimiento = ?, foto_doctor = ?, id_estado = ?,id_especialidad = ? WHERE id_doctor = ?';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $this->fecha, $this->foto,$this->estado,$this->especialidad, $this->iddoctor);
		return Database::executeRow($sql, $params);
	}

	public function updateProfile()
	{
		$sql = 'UPDATE doctores SET nombre_doctor = ?, apellido_doctor = ?, correo_doctor = ?, usuario_doctor = ?, fecha_nacimiento = ?, telefono_doctor = ? WHERE id_doctor = ?';
		$params = array($this->nombre, $this->apellido, $this->correo, $this->usuario, $this->fecha, $this->telefono, $this->iddoctor);
		return Database::executeRow($sql, $params);
	}

	public function changePassword()
	{
		$hash = password_hash($this->clave, PASSWORD_DEFAULT);
		$sql = 'UPDATE doctores SET contrasena_doctor = ? WHERE id_doctor = ?';
		$params = array($hash, $this->iddoctor);
		return Database::executeRow($sql, $params);
	}
}