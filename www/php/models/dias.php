<?php
class Dias extends Validator
{
    private $id = null;
    private $dias = null;

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
			$this->dias = $value; 
			return true;
		} else {
			return false;
		}
	}

	public function getDia()
	{
		return $this->dias;
    }

    //Métodos para manejar el CRUD
	public function readDias()
	{
		$sql = 'SELECT id_dia, dia FROM dias_disponibilidad ORDER BY id_dia ASC';
		$params = array(null);
		return Database::getRows($sql, $params);
	}
}