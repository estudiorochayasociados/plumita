<?php

namespace Clases;

class Galerias
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $desarrollo;
    public $categoria;
    public $keywords;
    public $description;
    public $fecha;

    private $con;
    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
        $this->categorias = new Categorias();
    }

    public function set($atributo, $valor)
    {
        if(!empty($valor)) {
            $valor = "'".$valor."'";
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
        $sql = "INSERT INTO `galerias`(`cod`, `titulo`, `desarrollo`, `categoria`, `keywords`, `description`, `fecha`) 
                  VALUES ({$this->cod},
                          {$this->titulo},
                          {$this->desarrollo},
                          {$this->categoria},
                          {$this->keywords},
                          {$this->description},
                          {$this->fecha})";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `galerias` 
                SET cod = {$this->cod},
                    titulo = {$this->titulo},
                    desarrollo = {$this->desarrollo},
                    categoria = {$this->categoria},
                    keywords = {$this->keywords},
                    description = {$this->description},
                    fecha = {$this->fecha} 
                WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `galerias` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sqlReturn($sql);
        if (!empty($this->imagenes->list(array("cod={$this->cod}"),'orden ASC',''))) {
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
        $sql = "SELECT * FROM `galerias` WHERE cod = {$this->cod} ORDER BY id DESC";
        $galeria_ = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($galeria_);
        $img = $this->imagenes->list(array("cod='".$row['cod']."'"),'orden ASC','');
        $this->categorias->set("cod",$row['categoria']);
        $cat = $this->categorias->view();
        $row_ = array("data" => $row, "category" => $cat['data'], "images" => $img);
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

        $sql = "SELECT * FROM `galerias` $filterSql  ORDER BY $orderSql $limitSql";
        $galerias = $this->con->sqlReturn($sql);

        if ($galerias) {
            while ($row = mysqli_fetch_assoc($galerias)) {
                $img = $this->imagenes->list(array("cod='".$row['cod']."'"),'orden ASC','');
                $this->categorias->set("cod",$row['categoria']);
                $cat = $this->categorias->view();
                $array[] = array("data" => $row, "category" => $cat['data'], "images" => $img);
            }
            return $array;
        }
    }
}
