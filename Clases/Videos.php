<?php namespace Clases;

class Videos
{
    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $descripcion;
    public $categoria;
    public $subcategoria;
    public $link;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->categoria = new Categorias();
        $this->subcategoria = new Subcategorias();
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
        $sql = "INSERT INTO `videos`(`cod`, `titulo`, `descripcion`, `categoria`, `subcategoria`, `link`) VALUES ({$this->cod},{$this->titulo},{$this->descripcion},{$this->categoria},{$this->subcategoria},{$this->link})";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `videos` SET `cod`={$this->cod},`titulo`={$this->titulo},`descripcion`={$this->descripcion},`categoria`={$this->categoria},`subcategoria`={$this->subcategoria},`link`={$this->link} WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `videos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `videos` WHERE cod = {$this->cod} ORDER BY id DESC";
        $videos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($videos);
        $this->categoria->set("cod", $row['categoria']);
        $cat = $this->categoria->view();
        $this->subcategoria->set("cod", $row['subcategoria']);
        $subcat = $this->subcategoria->view();
        $array = array("data" => $row, "category" => $cat, "subcategory" => $subcat);
        return $array;
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

        $sql = "SELECT * FROM `videos` $filterSql  ORDER BY $orderSql $limitSql";
        $videos = $this->con->sqlReturn($sql);

        if ($videos) {
            while ($row = mysqli_fetch_assoc($videos)) {
                $this->subcategoria->set("cod", $row['subcategoria']);
                $this->categoria->set("cod", $row['categoria']);
                $cat = $this->categoria->view();
                $subcat = $this->subcategoria->view();
                $array[] = array("data" => $row, "category" => $cat, "subcategory" => $subcat);
            }
            return $array;
        }
    }
}
