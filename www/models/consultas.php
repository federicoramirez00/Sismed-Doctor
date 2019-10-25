<?php
class Consultas extends Validator
{
    private $idconsulta = null;
    private $padecimientos = null;
    private $iddoctor = null;
    private $idpaciente = null;
    private $receta = null;
    private $idcita = null;
    private $fecha1 = null;
    private $fecha2 = null;
    private $mes = null;

    public function setIdConsulta($value)
    {
        if ($this->validateId($value)) {
			$this->idconsulta = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getIdConsulta()
    {
        return $this->idconsulta;
    }

    public function setPadecimientos($value)        
    {
        if ($this->validateAlphabetic($value, 1, 200)) {
			$this->padecimientos = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getPadecimientos()
    {
        return $this->padecimientos;
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

    public function setReceta($value)
    {
        if ($this->validateAlphanumeric($value, 1, 200)) {
			$this->receta = $value;
			return true;
		} else {
			return false;
		}
    }

    public function getReceta()
    {
        return $this->receta;
    }

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

    public function setFecha1($value)
    {
        $this->fecha1 = $value;
        return true;
    }

    public function setFecha2($value)
    {
        $this->fecha2 = $value;
        return true;
    }

    //método para setear el parámetro mes
    public function setMes($value)
    {
        $this->mes = $value;
        return true;
    }
    //métodos para mostrar las consultas por mes
    //utiliza una sub consulta para obtener los datos del mes, para luego obtener y mostar el nombre del mes y la cantidad de citas
    public function consultasPorFecha()
    {
        $sql = 'SELECT NumeroMes, NombreMes, CantidadCitas FROM (SELECT MONTH(fecha_cita) AS NumeroMes, COUNT(cn.id_cita) AS CantidadCitas, m.mes AS NombreMes FROM cita c INNER JOIN consulta cn USING(id_cita) INNER JOIN estado_cita e ON c.id_estado = e.id_estado INNER JOIN meses m WHERE c.id_estado = 4 AND MONTH(fecha_cita) = id_mes GROUP BY NumeroMes ORDER BY NumeroMes LIMIT 10) COUNTTABLE';
        $params = array(null);
        return Database::getRows($sql, $params);
    }

    public function showCitasEstadoDoctor()
    {
        $sql = 'SELECT COUNT(id_cita) AS Citas, id_doctor, nombre_doctor, apellido_doctor, c.id_estado, e.estado FROM cita c INNER JOIN doctores d USING(id_doctor) INNER JOIN estado_cita e ON c.id_estado = e.id_estado WHERE id_doctor = 3 GROUP BY id_estado ORDER BY id_doctor';
        $params = array(null);
        return Database::getRows($sql, $params);
    }
}