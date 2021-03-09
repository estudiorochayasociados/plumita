<?php

namespace Clases;

class Descuentos
{
    //Atributos
    public $id;
    public $titulo;
    public $tipo;
    public $monto;
    public $categorias_cod;
    public $subcategorias_cod;
    public $productos_cod;
    public $sector;
    public $fecha_inicio;
    public $fecha_fin;
    public $todosProductos;
    public $todasCategorias;
    public $todasSubcategorias;
    public $cod;

    private $con;
    private $imagenes;
    private $productos;
    private $carrito;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
        $this->productos = new Productos();
        $this->carrito = new Carrito();
    }

    public function set($atributo, $valor)
    {
        if (($atributo == "tipo" && empty($valor)) || ($atributo == "sector" && empty($valor))) {
            $valor = 0;
        } else {
            if (!empty($valor)) {
                $valor = "'" . $valor . "'";
            } else {
                $valor = "NULL";
            }
        }

        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;

    }

    public function add()
    {
        $sql = "INSERT INTO `descuentos`(`cod`,`titulo`,`tipo`,`monto`,`categorias_cod`,`subcategorias_cod`, `productos_cod`, `sector`, `fecha_inicio`, `fecha_fin`, `todos_productos`, `todas_categorias`, `todas_subcategorias`) 
                  VALUES ({$this->cod},
                          {$this->titulo},
                          {$this->tipo},
                          {$this->monto},
                          {$this->categorias_cod},
                          {$this->subcategorias_cod},
                          {$this->productos_cod},
                          {$this->sector},
                          {$this->fecha_inicio},
                          {$this->fecha_fin},
                          {$this->todosProductos},
                          {$this->todasCategorias},
                          {$this->todasSubcategorias})";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `descuentos` 
                  SET `titulo`={$this->titulo},
                      `cod`={$this->cod},
                      `tipo`={$this->tipo},
                      `monto`={$this->monto},
                      `categorias_cod`={$this->categorias_cod},
                      `subcategorias_cod`={$this->subcategorias_cod},
                      `productos_cod`={$this->productos_cod},
                      `sector`={$this->sector},
                      `fecha_inicio`={$this->fecha_inicio},
                      `fecha_fin`={$this->fecha_fin},
                      `todos_productos`={$this->todosProductos},
                      `todas_categorias`={$this->todasCategorias},
                      `todas_subcategorias`={$this->todasSubcategorias}
                  WHERE `id`={$this->id}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function refreshCartDescuento($carro, $usuario = NULL)
    {
        $codDescuento = [];
        krsort($carro);
        foreach ($carro as $key => $item) {
            if ($item['descuento']['status']) {
                $codDescuento[] = $item['id'];
                $this->carrito->delete($key);
                unset($carro[$key]);
            }
        }

        krsort($codDescuento);
        foreach ($codDescuento as $cod) {
            $this->set("cod", $cod);
            $this->addCartDescuento($carro, $usuario);
        }
    }

    public function addCartDescuento($carro, $usuario = NULL)
    {
        $price = 0;
        $errorNum = 0;
        $errorMsg = '';
        $descuentoAplicado = false;
        $productosEnDescuento = [];

        $descuentoData = $this->view();

        if ($descuentoData != NULL) {
            $efectivo = ($descuentoData['data']['tipo'] == 0) ? true : false;
            $porcentaje = ($descuentoData['data']['tipo'] == 1) ? true : false;
            $sectorTodos = ($descuentoData['data']['sector'] == 0) ? true : false;
            $sectorSinDescuento = ($descuentoData['data']['sector'] == 1 && ($usuario == NULL || empty($usuario['data']['descuento']))) ? true : false;
            $sectorConDescuento = ($descuentoData['data']['sector'] == 2 && !empty($usuario['data']['descuento'])) ? true : false;
            $fechaInicial = (strtotime($descuentoData['data']['fecha_inicio']) <= strtotime(strftime("%Y-%m-%d"))) ? true : false;
            $fechaFinal = (strtotime($descuentoData['data']['fecha_fin']) >= strtotime(strftime("%Y-%m-%d"))) ? true : false;
            $todosProductos = ($descuentoData['data']['todos_productos'] == 1) ? true : false;
            $todasCategorias = ($descuentoData['data']['todas_categorias'] == 1) ? true : false;
            $todasSubcategorias = ($descuentoData['data']['todas_subcategorias'] == 1) ? true : false;

            $arrayProductosDescuento = (explode(",", $descuentoData['data']['productos_cod']));
            $arrayCategoriasDescuento = (explode(",", $descuentoData['data']['categorias_cod']));
            $arraySubcategoriasDescuento = (explode(",", $descuentoData['data']['subcategorias_cod']));

            if ($sectorTodos || $sectorConDescuento || $sectorSinDescuento) {
                if ($fechaInicial) {
                    if ($fechaFinal) {
                        foreach ($carro as $item) {
                            if ($item["id"] != "Envio-Seleccion" && $item["id"] != "Metodo-Pago") {
                                if ($todosProductos || $todasCategorias || $todasSubcategorias) {
                                    if ($porcentaje) {
                                        $price += $item['cantidad'] * ($item['precio'] * $descuentoData['data']['monto'] / 100);
                                        $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-%" . $descuentoData['data']['monto']];
                                    }
                                    if ($efectivo) {
                                        $price += $item['cantidad'] * $descuentoData['data']['monto'];
                                        $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-$" . $descuentoData['data']['monto']];
                                    }
                                    $descuentoAplicado = true;
                                } else {
                                    $existeEnProductos = in_array($item['id'], $arrayProductosDescuento) ? true : false;
                                    $existeEnCategorias = in_array($item['id'], $arrayCategoriasDescuento) ? true : false;
                                    $existeEnSubcategorias = in_array($item['id'], $arraySubcategoriasDescuento) ? true : false;

                                    if ($existeEnProductos || $existeEnCategorias || $existeEnSubcategorias) {
                                        if ($porcentaje) {
                                            $price += $item['cantidad'] * ($item['precio'] * $descuentoData['data']['monto'] / 100);
                                            $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-%" . $descuentoData['data']['monto']];
                                        }
                                        if ($efectivo) {
                                            $price += $item['cantidad'] * $descuentoData['data']['monto'];
                                            $productosEnDescuento[] = ["titulo" => $item['titulo'], "monto" => "-$" . $descuentoData['data']['monto']];
                                        }
                                        $descuentoAplicado = true;
                                    }
                                }
                            }

                        }
                    } else {
                        $errorNum = 4;
                        $errorMsg = 'Este código ya venció.';
                    }
                } else {
                    $errorNum = 3;
                    $errorMsg = 'Este código aún no esta habilitado.';
                }
            } else {
                $errorNum = 2;
                $errorMsg = 'El código no es válido para este usuario.';
            }

        } else {
            $errorNum = 1;
            $errorMsg = 'El código no existe.';
        }

        if (!$descuentoAplicado) {
            if ($errorNum == 0) {
                $errorNum = 5;
                $errorMsg = 'Este código no aplica para ningún producto del carro.';
            }

            $status = ["applied" => false, "error" => ["errorNum" => $errorNum, "errorMsg" => $errorMsg]];
        } else {
            $this->carrito->set("id", $descuentoData['data']['cod']);
            $this->carrito->set("cantidad", 1);
            $this->carrito->set("titulo", $descuentoData['data']['titulo']);
            $this->carrito->set("precio", $price * (-1));
            $this->carrito->set("descuento", ['status' => true, 'products' => $productosEnDescuento]);
            $this->carrito->add();

            $status = ["applied" => true, "error" => ""];
        }

        return ["status" => $status];
    }

    public function delete()
    {
        $sql = "DELETE FROM `descuentos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);

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

    public
    function view()
    {
        $sql = "SELECT * FROM descuentos WHERE   cod = {$this->cod}  ";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        $img = $this->imagenes->list(array("cod='" . $row['cod'] . "'"), 'orden ASC', '');
        $row_ = array("data" => $row, "images" => $img);
        return $row_;
    }

    public
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
        $sql = "SELECT * FROM `descuentos` $filterSql  ORDER BY $orderSql $limitSql";
        $banners = $this->con->sqlReturn($sql);
        if ($banners) {
            while ($row = mysqli_fetch_assoc($banners)) {
                $img = $this->imagenes->list(array("cod='" . $row['cod'] . "'"), 'orden ASC', '');
                $array[] = array("data" => $row, "images" => $img);
            }
            return $array;
        }
    }
}
