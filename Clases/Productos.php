<?php

namespace Clases;

use Clases\Usuarios;

class Productos
{
    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $precio;
    public $precio_descuento;
    public $precio_mayorista;
    public $peso;
    public $stock;
    public $desarrollo;
    public $categoria;
    public $subcategoria;
    public $keywords;
    public $description;
    public $fecha;
    public $meli;
    public $variable1;
    public $variable2;
    public $variable3;
    public $variable4;
    public $variable5;
    public $variable6;
    public $variable7;
    public $variable8;
    public $variable9;
    public $variable10;
    public $cod_producto;
    public $img;
    public $url;

    //Clases
    protected $con;
    protected $f;
    private $categoriasClass;
    private $subcategoriasClass;
    private $imagenesClass;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->f = new PublicFunction();
        $this->categoriasClass = new Categorias();
        $this->subcategoriasClass = new Subcategorias();
        $this->imagenesClass = new Imagenes();

        $this->id = 'NULL';
        $this->cod = 'NULL';
        $this->titulo = 'NULL';
        $this->precio = 'NULL';
        $this->precio_descuento = 'NULL';
        $this->precio_mayorista = 'NULL';
        $this->peso = 'NULL';
        $this->stock = 'NULL';
        $this->desarrollo = 'NULL';
        $this->categoria = 'NULL';
        $this->subcategoria = 'NULL';
        $this->keywords = 'NULL';
        $this->description = 'NULL';
        $this->fecha = 'NULL';
        $this->meli = 'NULL';
        $this->variable1 = 'NULL';
        $this->variable2 = 'NULL';
        $this->variable3 = 'NULL';
        $this->variable4 = 'NULL';
        $this->variable5 = 'NULL';
        $this->variable6 = 'NULL';
        $this->variable7 = 'NULL';
        $this->variable8 = 'NULL';
        $this->variable9 = 'NULL';
        $this->variable10 = 'NULL';
        $this->cod_producto = 'NULL';
        $this->img = 'NULL';
        $this->url = 'NULL';
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
        $sql = "INSERT INTO `productos`(`cod`, `titulo`,`cod_producto`, `precio`,`precio_descuento`,`precio_mayorista`, `variable1`,`variable2`,`variable3`,`variable4`,`variable5`,`variable6`,`variable7`,`variable8`,`variable9`,`variable10`,  `stock`, `peso`, `desarrollo`, `categoria`, `subcategoria`, `keywords`, `description`, `fecha`, `meli`, `url`) 
                VALUES ({$this->cod},
                        {$this->titulo},
                        {$this->cod_producto},
                        {$this->precio},
                        {$this->precio_descuento},
                        {$this->precio_mayorista},
                        {$this->variable1},
                        {$this->variable2},
                        {$this->variable3},
                        {$this->variable4},
                        {$this->variable5},
                        {$this->variable6},
                        {$this->variable7},
                        {$this->variable8},
                        {$this->variable9},
                        {$this->variable10},
                        {$this->stock},
                        {$this->peso},
                        {$this->desarrollo},
                        {$this->categoria},
                        {$this->subcategoria},
                        {$this->keywords},
                        {$this->description},
                        NOW(),
                        {$this->meli},
                        {$this->url})";
        $query = $this->con->sqlReturn($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `productos` 
                SET `cod` = {$this->cod},
                    `titulo` = {$this->titulo},
                    `precio` = {$this->precio},
                    `precio_descuento` = {$this->precio_descuento},
                    `precio_mayorista` = {$this->precio_mayorista},
                    `cod_producto` = {$this->cod_producto},
                    `stock` = {$this->stock},
                    `peso` = {$this->peso},
                    `desarrollo` = {$this->desarrollo},
                    `categoria` = {$this->categoria},
                    `subcategoria` = {$this->subcategoria},
                    `keywords` = {$this->keywords},
                    `description` = {$this->description},
                    `variable1` = {$this->variable1},
                    `variable2` = {$this->variable2},
                    `variable3` = {$this->variable3},
                    `variable4` = {$this->variable4},
                    `variable5` = {$this->variable5},
                    `variable6` = {$this->variable6},
                    `variable7` = {$this->variable7},
                    `variable8` = {$this->variable8},
                    `variable9` = {$this->variable9},
                    `variable10` = {$this->variable10},
                    `fecha` = {$this->fecha},
                    `meli` = {$this->meli},
                    `url` = {$this->url}
                WHERE `id`={$this->id}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function editSingle($atributo, $valor)
    {
        $sql = "UPDATE `productos` SET `$atributo` = {$valor} WHERE `cod`={$this->cod}";
        $this->con->sql($sql);
    }

    public function editSingleWithApostrophe($atributo, $valor)
    {
        $sql = "UPDATE `productos` SET `$atributo` = '{$valor}' WHERE `cod`={$this->cod}";
        $this->con->sql($sql);
    }
    public function delete()
    {
        $sql = "DELETE FROM `productos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($this->imagenesClass->list(array("cod={$this->cod}"), '', ''))) {
            $this->imagenesClass->cod = $this->cod;
            $this->imagenesClass->deleteAll();
        }

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function import()
    {
        $sql = "({$this->cod},
                 {$this->titulo},
                 {$this->cod_producto},
                 {$this->precio},
                 {$this->precio_descuento},
                 {$this->precio_mayorista},
                 {$this->variable1},
                 {$this->variable2},
                 {$this->variable3},
                 {$this->variable4},
                 {$this->variable5},
                 {$this->variable6},
                 {$this->variable7},
                 {$this->variable8},
                 {$this->variable9},
                 {$this->variable10},
                 {$this->stock},
                 {$this->peso},
                 {$this->desarrollo},
                 {$this->categoria},
                 {$this->subcategoria},
                 {$this->keywords},
                 {$this->description},
                 {$this->fecha},
                 {$this->meli},
                 {$this->url}),";
        return $sql;
    }

    public function query($sql)
    {
        $querySql = "INSERT INTO `productos`(`cod`, `titulo`,`cod_producto`, `precio`,`precio_descuento`,`precio_mayorista`, `variable1`,`variable2`,`variable3`,`variable4`,`variable5`,`variable6`,`variable7`,`variable8`,`variable9`,`variable10`,  `stock`, `peso`, `desarrollo`, `categoria`, `subcategoria`, `keywords`, `description`, `fecha`, `meli`, `url`) 
                VALUES " . $sql;
        $query = $this->con->sql($querySql);
        return $query;
    }

    public function truncate()
    {
        $sql = "DELETE FROM productos WHERE `meli` is NULL OR `meli` = ''";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `productos` WHERE  cod = {$this->cod} LIMIT 1";
        $productos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($productos);
        if ($productos->num_rows != 0) {
            $row = !empty($row) ? $this->checkPriceByUser($row) : '';
            //$img = $this->imagenesClass->listValidation($row['cod']);
            $img = $this->imagenesClass->checkForProduct($row['cod'], $row['cod_producto']);
            $this->categoriasClass->set("cod", $row['categoria']);
            $cat = $this->categoriasClass->view();
            $this->subcategoriasClass->set("cod", $row['subcategoria']);
            $subcat = $this->subcategoriasClass->view();
            $link = URL . '/producto/' . $this->f->normalizar_link($row['titulo']) . '/' . $this->f->normalizar_link($row['cod']);
            $array = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img, "link" => $link);
            return $array;
        }
    }

    public function viewByCod($cod_producto)
    {
        $sql = "SELECT * FROM `productos` WHERE  cod_producto = '$cod_producto' LIMIT 1";
        $productos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($productos);
        if ($productos->num_rows != 0) {
            $row = !empty($row) ? $this->checkPriceByUser($row) : '';
            //$img = $this->imagenesClass->listValidation($row['cod']);
            $img = $this->imagenesClass->checkForProduct($row['cod'], $row['cod_producto']);
            $this->categoriasClass->set("cod", $row['categoria']);
            $cat = $this->categoriasClass->view();
            $this->subcategoriasClass->set("cod", $row['subcategoria']);
            $subcat = $this->subcategoriasClass->view();
            $link = URL . '/producto/' . $this->f->normalizar_link($row['titulo']) . '/' . $this->f->normalizar_link($row['cod']);
            $array = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img, "link" => $link);
            return $array;
        }
    }

    function list($filter, $order, $limit)
    {
        $array = [];
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = $filter;
        }

        !empty($order) ? $orderSql = $order : $orderSql = "id DESC";

        !empty($limit) ? $limitSql = "LIMIT " . $limit : $limitSql = '';

        $sql = "SELECT * FROM `productos` $filterSql ORDER BY $orderSql $limitSql";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $this->set("cod", $row["cod"]);
                $row = !empty($row) ? $this->checkPriceByUser($row) : '';
                //$img = $this->imagenesClass->listValidation($row['cod']);
                $img = $this->imagenesClass->checkForProduct($row['cod'], $row['cod_producto']);
                $this->categoriasClass->set("cod", $row['categoria']);
                $cat = $this->categoriasClass->view();
                $this->subcategoriasClass->set("cod", $row['subcategoria']);
                $subcat = $this->subcategoriasClass->view();
                $link = URL . '/producto/' . $this->f->normalizar_link($row['titulo']) . '/' . $this->f->normalizar_link($row['cod']);
                $array[] = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img, "link" => $link);
            }
            return $array;
        }
    }


    public function paginador($filter, $cantidad)
    {
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `productos` $filterSql";
        $contar = $this->con->sqlReturn($sql);
        $total = mysqli_num_rows($contar);
        $totalPaginas = $total / $cantidad;
        return ceil($totalPaginas);
    }

    function listForManyCods($cods)
    {
        $array = [];

        $productosFilterStr = '';
        foreach ($cods as $cod) {
            $productosFilterStr .= "cod = '" . $cod . "' OR ";
        }
        $productosFilterStr = substr($productosFilterStr, 0, -4);

        $sql = "SELECT * FROM `productos` WHERE $productosFilterStr ";
        $producto = $this->con->sqlReturn($sql);
        if ($producto) {
            while ($row = mysqli_fetch_assoc($producto)) {
                $img = $this->imagenesClass->list(array("cod = '" . $row['cod'] . "'"), "orden ASC", "");
                $this->categoriasClass->set("cod", $row['categoria']);
                $cat = $this->categoriasClass->view();
                $this->subcategoriasClass->set("cod", $row['subcategoria']);
                $subcat = $this->subcategoriasClass->view();
                $link = URL . '/producto/' . $this->f->normalizar_link($row['titulo']) . '/' . $this->f->normalizar_link($row['cod']);
                $row['desarrollo'] = '';
                $array[] = array("data" => $row, "category" => $cat, "subcategory" => $subcat, "images" => $img, "link" => $link);
            }
            return $array;
        }
    }

    function listVariable($variable)
    {
        $sql = "SELECT DISTINCT $variable FROM `productos` ORDER BY $variable";
        $var = $this->con->sqlReturn($sql);
        if ($var) {
            while ($row = mysqli_fetch_assoc($var)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }

    //Especiales
    public function getVariables($var, $category)
    {
        if ($category != '') {
            $sql = "SELECT `$var` FROM `productos` WHERE `$var`!='' and `categoria`='$category' GROUP BY `$var` ORDER BY `$var`  DESC";
        } else {
            $sql = "SELECT `$var` FROM `productos` WHERE `$var`!='' GROUP BY `$var` ORDER BY `$var`  DESC";
        }
        $dimensions = $this->con->sqlReturn($sql);
        if ($dimensions) {
            while ($row = mysqli_fetch_assoc($dimensions)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }


    public function checkPriceByUser($product)
    {
        $user = new Usuarios();
        $userSession = (isset($_SESSION["usuarios"]["cod"])) ? $user->refreshSession($_SESSION["usuarios"]["cod"]) : '';
        if (!empty($userSession)) {
            if (!empty($userSession["minorista"])) {
                $product["precio"] = empty($userSession["descuento"]) ? $product['precio'] : $product['precio'] - (($product['precio'] * $userSession['descuento']) / 100);
                $product['precio_descuento'] = empty($userSession["descuento"]) ? $product['precio_descuento'] : $product['precio_descuento'] - (($product['precio_descuento'] * $userSession['descuento']) / 100);
            } else {
                $product["precio"] = empty($userSession["descuento"]) ? $product['precio'] : $product['precio'] - (($product['precio'] * $userSession['descuento']) / 100);
                if (!empty($product["precio_mayorista"])) {
                    $product['precio_descuento'] = empty($userSession["descuento"]) ? $product['precio_mayorista'] : $product['precio_mayorista'] - (($product['precio_mayorista'] * $userSession['descuento']) / 100);
                } else {
                    $product['precio_descuento'] = empty($userSession["descuento"]) ? $product['precio'] : $product['precio'] - (($product['precio'] * $userSession['descuento']) / 100);
                }
            }
        }

        $product["precio_final"] = !empty($product["precio_descuento"]) ? $product["precio_descuento"] : $product["precio"];

        return $product;
    }
}
