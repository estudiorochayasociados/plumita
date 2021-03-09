<?php
namespace Clases;

class Contenidos 
{
    //Atributos
    public $id;
    public $titulo;
    public $subtitulo;
    public $contenido;
    public $cod;

    private $con;
    private $imagenes;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
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
        $sql = "INSERT INTO `contenidos`(`titulo`,`subtitulo`,`contenido`, `cod`) 
                  VALUES ({$this->titulo},
                          {$this->subtitulo},
                          {$this->contenido},
                          {$this->cod})";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `contenidos` 
                  SET `titulo`={$this->titulo},
                      `subtitulo`={$this->subtitulo},
                      `contenido`={$this->contenido}
                  WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `contenidos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($this->imagenes->list(array("cod={$this->cod}"), 'orden ASC', ''))) {
            $this->imagenes->cod = $this->cod;
            $this->imagenes->deleteAll();
        }

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM contenidos WHERE  cod = {$this->cod}  ";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        $img = $this->imagenes->list(array("cod='" . $row['cod'] . "'"), 'orden ASC', '');
        $row_ = array("data" => $row, "images" => $img);
        return $row_;
    }

    public function list($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "id DESC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT * FROM `contenidos` $filterSql  ORDER BY $orderSql $limitSql";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $img = $this->imagenes->list(array("cod='" . $row['cod'] . "'"), 'orden ASC', '');
                $array[] = array("data" => $row, "images" => $img);
            }
            return $array;
        }
    }
}
