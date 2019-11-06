<?php

namespace Clases;

class ServicioTecnico
{
    private $db = "servicio_tecnico";

    private $tecnico;
    private $direccion;
    private $ciudad;
    private $provincia;
    private $telefono;
    private $email;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        if (!empty($valor)) {
            $valor = "'" . $valor . "'";
        } else {
            $valor = "NULL";
        }
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql = "INSERT INTO `$this->db`(`tecnico`,`direccion`,`ciudad`,`provincia`,  `telefono`, `email`) 
                  VALUES ({$this->tecnico},
                          {$this->direccion},
                          {$this->ciudad},
                          {$this->provincia},
                          {$this->telefono},
                          {$this->email})";

        if ($this->con->sqlReturn($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "TRUNCATE TABLE $this->db";
        $this->con->sql($sql);
    }

    public function listProvincias()
    {
        $sql = "SELECT provincia FROM `$this->db` GROUP BY provincia ASC";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    public function listCiudad($provincia)
    {
        $array = [];
        $provincia = htmlentities($provincia);
        $sql = "SELECT ciudad FROM `$this->db` WHERE provincia='$provincia' GROUP BY ciudad";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $array[] = $row;
            }
        }
        return $array;
    }

    public function list($filter, $order, $limit)
    {
        $array = [];
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $orderSql = (!empty($order) ? $order : "id DESC");

        $limitSql = (!empty($limit) ? "LIMIT " . $limit : '');

        $sql = "SELECT * FROM `$this->db` $filterSql  ORDER BY $orderSql $limitSql";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }

    public function listForProvincia($provincia)
    {
        $array = [];
        $provincia = htmlentities($provincia);
        if (!empty($provincia)) {
            $sql = "SELECT * FROM `$this->db` WHERE provincia='$provincia'";
        } else {
            $sql = "SELECT * FROM `$this->db`";
        }

        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }

    public function listForCiudad($ciudad)
    {
        $array = [];
        $ciudad = htmlentities($ciudad);

        $sql = "SELECT * FROM `$this->db` WHERE ciudad='$ciudad'";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $array[] = array("data" => $row);
            }
        }
        return $array;
    }
}