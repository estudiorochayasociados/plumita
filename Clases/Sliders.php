<?php

namespace Clases;

class Sliders
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $tituloOn;
    public $subtitulo;
    public $subtituloOn;
    public $categoria;
    public $link;
    public $linkOn;
    public $fecha;
    private $con;

    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes=new Imagenes();
        $this->categorias=new Categorias();
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
        $sql   = "INSERT INTO `sliders`(`cod`, `titulo`, `titulo_on`, `subtitulo`, `subtitulo_on`,  `categoria`,  `link`,  `link_on`, `fecha`) 
                  VALUES ({$this->cod},
                          {$this->titulo},
                          {$this->tituloOn},
                          {$this->subtitulo},
                          {$this->subtituloOn},
                          {$this->categoria} ,
                          {$this->link},
                          {$this->linkOn},
                          {$this->fecha})";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql   = "UPDATE `sliders` 
                  SET cod = {$this->cod},
                      titulo = {$this->titulo},
                      titulo_on = {$this->tituloOn},
                      subtitulo = {$this->subtitulo},
                      subtitulo_on = {$this->subtituloOn},
                      categoria = {$this->categoria},
                      link = {$this->link},
                      link_on = {$this->linkOn},
                      fecha = {$this->fecha} 
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

        $sql   = "DELETE FROM `sliders` WHERE `cod`  = {$this->cod}";
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
        $sql   = "SELECT * FROM `sliders` WHERE cod = {$this->cod} ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        $img = $this->imagenes->view($row["cod"]);
        $this->categorias->set("cod",$row['categoria']);
        $cat = $this->categorias->view();
        $row_ = array("data" => $row, "category" => $cat['data'], "image" => $img);
        return $row_;
    }

    function list($filter, $order, $limit) {
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

        $sql   = "SELECT * FROM `sliders` $filterSql  ORDER BY $orderSql $limitSql";
        $slider = $this->con->sqlReturn($sql);

        if ($slider) {
            while ($row = mysqli_fetch_assoc($slider)) {
                $this->imagenes->set("cod",$row['cod']);
                $img = $this->imagenes->view($row['cod']);
                $this->categorias->set("cod",$row['categoria']);
                $cat = $this->categorias->view();
                $array[] = array("data" => $row, "category" => $cat['data'], "image" => $img);
            }
            return $array;
        }
    }
}
