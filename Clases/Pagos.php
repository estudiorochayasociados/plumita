<?php

namespace Clases;

class Pagos
{

    //Atributos
    public $id;
    public $titulo;
    public $leyenda;
    public $cod;
    public $estado;
    public $aumento;
    public $disminuir;
    public $defecto;
    public $tipo;

    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        if ($valor != '') {
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
        $sql = "INSERT INTO `pagos`(`titulo`, `leyenda`, `cod`, `estado`, `aumento`, `disminuir`, `defecto`,`tipo`) 
                VALUES ({$this->titulo},
                        {$this->leyenda},
                        {$this->cod},
                        {$this->estado},
                        {$this->aumento},
                        {$this->disminuir},
                        {$this->defecto},
                        {$this->tipo})";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `pagos` 
                SET  `titulo` = {$this->titulo},
                    `leyenda` = {$this->leyenda},
                    `estado` = {$this->estado},
                    `aumento` = {$this->aumento},
                    `disminuir` = {$this->disminuir},
                    `defecto` = {$this->defecto},
                    `tipo` = {$this->tipo} 
                WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function changeState()
    {
        $sql = "UPDATE `pagos` SET `estado`={$this->estado} WHERE `cod`={$this->cod}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `pagos` WHERE `cod`  = {$this->cod}";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view()
    {
        $sql = "SELECT * FROM `pagos` WHERE cod = {$this->cod} ORDER BY id DESC";
        $pagos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($pagos);
        $row_ = array("data" => $row);
        return $row_;
    }

    function list($filter = '', $order = '', $limit = '')
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

        $sql = "SELECT * FROM `pagos` $filterSql  ORDER BY $orderSql $limitSql";
        $pagos = $this->con->sqlReturn($sql);

        if ($pagos) {
            while ($row = mysqli_fetch_assoc($pagos)) {
                $array[] = array("data" => $row);
            }
            return $array;
        }
    }

    public function getApiPayment($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error !== '') {
            throw new \Exception($error);
        }

        return $response;
    }

    public function checkPriceOnPayments($pago, $precio)
    {
        if ($pago['data']["aumento"] != 0 || $pago['data']["disminuir"] != 0) {
            if ($pago['data']["aumento"] > 0) {
                $precio = (($precio * $pago['data']["aumento"]) / 100) + $precio;
            } else {
                $precio = $precio - (($precio * $pago['data']["disminuir"]) / 100);
            }
        }
        return number_format($precio, 2, ".", "");
    }
}
