<?php


namespace Clases;


class Atributos
{
    //Atributos
    public $id;
    public $productoCod;
    public $cod;
    public $value;

    private $subatributos;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->subatributos = new Subatributos();
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

    public function add()
    {
        $sql = "INSERT INTO `atributos`(`cod`,`cod_producto`, `value`) 
                  VALUES ({$this->cod},{$this->productoCod},{$this->value})";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `atributos` 
                  SET  `value` =  {$this->value}  
                  WHERE `cod`= {$this->cod} ";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function delete()
    {
        $sql = "DELETE FROM `atributos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sqlReturn($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM `atributos` WHERE cod = {$this->cod} ORDER BY id DESC LIMIT 1";
        $atributo = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($atributo);
        $this->subatributos->set("codAtributo", $row['cod']);
        $subatributes = $this->subatributos->list();
        $row["subatributes"] = $subatributes;
        $array = array("atribute" => $row);
        return $array;
    }

    public function list()
    {
        $array = array();
        $sql = "SELECT * FROM `atributos` WHERE cod_producto={$this->productoCod}";
        $data = $this->con->sqlReturn($sql);
        if ($data) {
            while ($row = mysqli_fetch_assoc($data)) {
                $this->subatributos->set("codAtributo", $row['cod']);
                $subatributes = $this->subatributos->list();
                $row["subatributes"] = $subatributes;
                $array[] = array("atribute" => $row);
            }
            return $array;
        }
    }


    public function checkAtributeOnAddCart($atribute = null)
    {
        if ($atribute) {
            $opcion = '| ';
            $atri = array();
            foreach ($atribute as $key => $atrib) {
                $this->set("cod", $key);
                $titulo = $this->view()['atribute']['value'];
                $this->subatributos->set("cod", $atrib);
                $sub = $this->subatributos->view()['data']['value'];
                $opcion .= "<strong>$titulo: </strong>$sub | ";
                $atri[] = array($titulo => $sub);
            }
            return array("texto" => $opcion, "subatributos" => $atri);
        } else {
            return false;
        }
    }
}