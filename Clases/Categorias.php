<?php

namespace Clases;

use function GuzzleHttp\Psr7\str;

class Categorias
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $area;
    public $descripcion;

    private $con;
    private $subcategoria;
    private $imagenes;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->subcategoria = new Subcategorias();
        $this->imagenes = new Imagenes();
    }

    public function set($atributo, $valor)
    {
        if (strlen($valor)) {
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
        $sql = "INSERT INTO `categorias`(`cod`, `titulo`, `area`, `descripcion`) 
                  VALUES ({$this->cod},{$this->titulo},{$this->area},{$this->descripcion})";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `categorias` 
                  SET cod = {$this->cod} ,
                      titulo = {$this->titulo} ,
                      area = {$this->area},  
                      descripcion = {$this->descripcion}  
                  WHERE `id`= {$this->id} ";
        $query = $this->con->sql($sql);
        return true;
    }


    public function delete()
    {
        $sql = "DELETE FROM `categorias` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sqlReturn($sql);
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
        $sql = "SELECT * FROM `categorias` WHERE cod = {$this->cod}  LIMIT 1";
        $categorias = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($categorias);
        $img = $this->imagenes->view($row['cod']);
        $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
        $row_ = array("data" => $row, "subcategories" => $sub, "image" => $img);
        return $row_;
    }

    public function viewByTitle($value = null)
    {
        if ($value != '') {
            $value = str_replace("-", " ", $value);
            $sql = "SELECT * FROM `categorias` WHERE titulo = '$value' LIMIT 1";
            $categorias = $this->con->sqlReturn($sql);
            $row = mysqli_fetch_assoc($categorias);
            $img = $this->imagenes->view($row['cod']);
            $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
            $row_ = array("data" => $row, "subcategories" => $sub, "image" => $img);
            return $row_;
        } else {
            return false;
        }
    }

    public function viewById($cod = null)
    {
        if ($cod != '') {
            $cod = str_replace("-", " ", $cod);
            $sql = "SELECT * FROM `categorias` WHERE cod = '$cod' LIMIT 1";
            $categorias = $this->con->sqlReturn($sql);
            $row = mysqli_fetch_assoc($categorias);
            $img = $this->imagenes->view($row['cod']);
            $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
            $row_ = array("data" => $row, "subcategories" => $sub, "image" => $img);
            return $row_;
        } else {
            return false;
        }
    }
    public function viewByCod($cod = null)
    {
        if ($cod != '') {
            $cod = str_replace("-", " ", $cod);
            $sql = "SELECT * FROM `categorias` WHERE cod = '$cod' LIMIT 1";
            $categorias = $this->con->sqlReturn($sql);
            $row = mysqli_fetch_assoc($categorias);
            $img = $this->imagenes->view($row['cod']);
            $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
            $row_ = array("data" => $row, "subcategories" => $sub, "image" => $img);
            return $row_;
        } else {
            return false;
        }
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

        $sql = "SELECT * FROM `categorias` $filterSql  ORDER BY $orderSql $limitSql";
        $categorias = $this->con->sqlReturn($sql);
        if ($categorias) {
            while ($row = mysqli_fetch_assoc($categorias)) {
                $img = $this->imagenes->view($row['cod']);
                $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
                $array[] = array("data" => $row, "subcategories" => $sub, "image" => $img);
            }
            return $array;
        }
    }

    public function listIfHave($db, $offer = '')
    {
        $array = array();
        $sql = " SELECT `categorias`.`titulo`,`categorias`.`cod`,`categorias`.`id`, count(`" . $db . "`.`categoria`) as cantidad FROM `" . $db . "`,`categorias` WHERE `variable9` = 1 AND `categoria` = `categorias`.`cod` $offer GROUP BY categoria ORDER BY titulo ASC";
        $listIfHave = $this->con->sqlReturn($sql);
        if ($listIfHave) {
            while ($row = mysqli_fetch_assoc($listIfHave)) {
                $img = $this->imagenes->view($row['cod']);
                $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
                $array[] = array("data" => $row, "subcategories" => $sub, "image" => $img);
            }
            return $array;
        }
    }

    public function listIfHaveOffert($db, $offer = '')
    {
        $array = array();
        $sql = " SELECT `categorias`.`titulo`,`categorias`.`cod`,`categorias`.`id`, count(`" . $db . "`.`categoria`) as cantidad FROM `" . $db . "`,`categorias` WHERE `variable9` = 1 AND `precio_descuento` > 0 AND `categoria` = `categorias`.`cod` $offer GROUP BY categoria ORDER BY titulo ASC";
        $listIfHave = $this->con->sqlReturn($sql);
        if ($listIfHave) {
            while ($row = mysqli_fetch_assoc($listIfHave)) {
                $img = $this->imagenes->view($row['cod']);
                $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
                $array[] = array("data" => $row, "subcategories" => $sub, "image" => $img);
            }
            return $array;
        }
    }

    public function listForManyCods($cods)
    {

        $array = [];

        $categoriasFilterStr = '';
        foreach ($cods as $cod) {
            $categoriasFilterStr .= "cod = '" . $cod . "' OR ";
        }
        $categoriasFilterStr = substr($categoriasFilterStr, 0, -4);

        $sql = "SELECT * FROM `categorias` WHERE $categoriasFilterStr";
        $categorias = $this->con->sqlReturn($sql);
        if ($categorias) {
            while ($row = mysqli_fetch_assoc($categorias)) {
                $img = $this->imagenes->view($row['cod']);
                $sub = $this->subcategoria->list(array("categoria='" . $row['cod'] . "'"), '', '');
                $array[] = array("data" => $row, "subcategories" => $sub, "image" => $img);
            }
            return $array;
        }
    }

    public function listSubcategoriesForManyCods($cods)
    {

        $categoriasArray = $this->list(["area = 'productos'"], "", "");
        $subcategoriasSavedArray = [];
        foreach ($categoriasArray as $categoria) {
            foreach ($categoria["subcategories"] as $subcategoria) {
                $subcategoria["categoriaTitulo"] = $categoria['data']['titulo'];
                if (in_array($subcategoria['data']['cod'], $cods)) {
                    $subcategoriasSavedArray[] = $subcategoria;
                }
            }
        }

        return $subcategoriasSavedArray;
    }
}
