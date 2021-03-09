<?php

namespace Clases;

class Servicios
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $desarrollo;
    public $categoria;
    public $subcategoria;
    public $keywords;
    public $description;
    public $fecha;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->categorias = new Categorias();
        $this->subcategorias = new Subcategorias();
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
        $sql = "INSERT INTO `servicios`(`cod`, `titulo`, `desarrollo`, `categoria`,`subcategoria`, `keywords`, `description`, `fecha`) 
                VALUES ({$this->cod},
                        {$this->titulo},
                        {$this->desarrollo},
                        {$this->categoria},
                        {$this->subcategoria},
                        {$this->keywords},
                        {$this->description},
                        {$this->fecha})";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `servicios` 
                SET cod = {$this->cod},
                    titulo = {$this->titulo},
                    desarrollo = {$this->desarrollo},
                    categoria = {$this->categoria},
                    subcategoria = {$this->subcategoria},
                    keywords = {$this->keywords},
                    description = {$this->description},
                    fecha = {$this->fecha} 
                WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `servicios` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($this->imagenes->list(array("cod=$this->cod"), 'orden ASC', ''))) {
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
        $sql = "SELECT * FROM `servicios` WHERE cod = {$this->cod} ORDER BY id DESC LIMIT 1";
        $servicio = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($servicio);
        $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"), "orden ASC", "");
        $this->categorias->set("cod", $row["categoria"]);
        $cat = $this->categorias->view();
        $this->subcategorias->set("cod", $row["subcategoria"]);
        $subcat = $this->subcategorias->view();
        $array = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img);
        return $array;
    }

    function list($filter, $order, $limit)
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

        $sql = "SELECT * FROM `servicios` $filterSql ORDER BY $orderSql $limitSql";
        $servicio = $this->con->sqlReturn($sql);
        if ($servicio) {
            while ($row = mysqli_fetch_assoc($servicio)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"), "orden ASC", "");
                $this->categorias->set("cod", $row['categoria']);
                $cat = $this->categorias->view();
                $this->subcategorias->set("cod", $row['subcategoria']);
                $subcat = $this->subcategorias->view();
                $array[] = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img);
            }
            return $array;
        }
    }
}
