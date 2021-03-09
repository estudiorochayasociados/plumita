<?php

namespace Clases;

class Novedades
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
        $sql = "INSERT INTO `novedades`(`cod`, `titulo`, `desarrollo`, `categoria`,`subcategoria`, `keywords`, `description`, `fecha`) 
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
        $sql = "UPDATE `novedades` 
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
        $sql = "DELETE FROM `novedades` WHERE `cod`  = {$this->cod}";
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
        $sql = "SELECT * FROM `novedades` WHERE cod = {$this->cod} ORDER BY id DESC LIMIT 1";
        $novedad = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($novedad);
        $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"), "orden ASC", "");
        $this->categorias->set("cod", $row['categoria']);
        $cat = $this->categorias->view();
        $this->subcategorias->set("cod", $row['subcategoria']);
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

        $sql = "SELECT * FROM `novedades` $filterSql ORDER BY $orderSql $limitSql";
        $novedad = $this->con->sqlReturn($sql);
        if ($novedad) {
            while ($row = mysqli_fetch_assoc($novedad)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"), "orden ASC", "");
                $this->subcategorias->set("cod", $row['subcategoria']);
                $this->categorias->set("cod", $row['categoria']);
                $cat = $this->categorias->view();
                $subcat = $this->subcategorias->view();
                $array[] = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img);
            }
            return $array;
        }
    }

    function paginador($filter, $cantidad)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `novedades` $filterSql";
        $contar = $this->con->sqlReturn($sql);
        $total = mysqli_num_rows($contar);
        $totalPaginas = $total / $cantidad;
        return ceil($totalPaginas);
    }
}
