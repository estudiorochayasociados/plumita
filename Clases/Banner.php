<?php

namespace Clases;

class Banner
{

    //Atributos
    public $id;
    public $cod;
    public $nombre;
    public $categoria;
    public $link;
    public $vistas;

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
        $sql = "INSERT INTO `banners`(`cod`, `nombre`, `categoria`, `vistas`,`link`) 
                VALUES ({$this->cod},
                        {$this->nombre},
                        {$this->categoria},
                        0,
                        {$this->link})";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function increaseViews()
    {
        $sql = "UPDATE `banners` SET vistas = '{$this->vistas}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }
    public function edit()
    {
        $sql = "UPDATE `banners` 
                SET cod = {$this->cod},
                    nombre = {$this->nombre},
                    categoria = {$this->categoria},
                    link = {$this->link} 
                WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function editSingle($atribute, $value)
    {
        $sql = "UPDATE `banners` SET $atribute = $value WHERE `id`={$this->id} OR `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `banners` WHERE `cod`  = {$this->cod}";
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
        $sql = "SELECT * FROM `banners` WHERE cod = {$this->cod} ORDER BY cod DESC";
        $banner = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($banner);
        $img = $this->imagenes->view($row['cod']);
        $this->categorias->set("cod",$row['categoria']);
        $cat = $this->categorias->view();
        $row_ = array("data" => $row, "category" => $cat['data'], "image" => $img);
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

        $sql = "SELECT * FROM `banners` $filterSql  ORDER BY $orderSql $limitSql";
        $banners = $this->con->sqlReturn($sql);

        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $img = $this->imagenes->view($row['cod']);
                $this->categorias->set("cod",$row['categoria']);
                $cat = $this->categorias->view();
                $array[] = array("data" => $row, "category" => $cat['data'], "image" => $img);
            }
            return $array;
        }
    }
}
