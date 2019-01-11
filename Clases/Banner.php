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

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql   = "INSERT INTO `banners`(`cod`, `nombre`, `categoria`, `vistas`,`link`) VALUES ('{$this->cod}', '{$this->nombre}', '{$this->categoria}', '{$this->vistas}', '{$this->link}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `banners` SET cod = '{$this->cod}', nombre = '{$this->nombre}', categoria = '{$this->categoria}', vistas = '{$this->vistas}', link = '{$this->link}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `banners` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `banners` WHERE cod = '{$this->cod}' ORDER BY cod DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        return $row;
    }

    public function list() {
        $array = array();
        $sql = "SELECT * FROM `banners`";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array ;
        }
    }

    public function increaseViews()
    {
        $sql   = "UPDATE `banners` SET vistas = '{$this->vistas}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    function listForCategory() {
        $array = array();
        $sql = "SELECT * FROM `banners` WHERE categoria = '{$this->categoria}'  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
