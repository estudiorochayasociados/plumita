<?php

namespace Clases;

class Usuarios
{

    //Atributos
    public $id;
    public $cod;
    public $nombre;
    public $apellido;
    public $doc;
    public $email;
    public $password;
    public $direccion;
    public $postal;
    public $localidad;
    public $provincia;
    public $pais;
    public $telefono;
    public $celular;
    public $minorista;
    public $invitado;
    public $descuento;
    public $fecha;
    public $estado;

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

    public function transformQuery()
    {
        $atributes = array("cod" => $this->cod, "nombre" => $this->nombre, "apellido" => $this->apellido, "doc" => $this->doc, "email" => $this->email, "password" => $this->password, "direccion" => $this->direccion, "postal" => $this->postal, "localidad" => $this->localidad, "provincia" => $this->provincia, "pais" => $this->pais, "telefono" => $this->telefono, "celular" => $this->celular, "minorista" => $this->minorista, "invitado" => $this->invitado, "descuento" => $this->descuento, "fecha" => $this->fecha, "estado" => $this->estado);

        foreach ($atributes as $name => $value) {
            if (strlen($value)) {
                $valor = "'" . $value . "'";
            } else {
                $valor = "NULL";
            }
            $this->$name = $valor;
        }
    }

    public function hash()
    {
        return hash('sha256', $this->password . SALT);
    }

    public function add()
    {
        $validar = $this->validate();
        if (!$validar['status']) {
            if (!empty($this->password)) {
                $this->set("password", hash('sha256', $this->password . SALT));
            }
            $this->transformQuery();
            $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `password`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `minorista`, `invitado`, `descuento`, `fecha`, `estado`) 
                    VALUES ({$this->cod},
                            {$this->nombre},
                            {$this->apellido},
                            {$this->doc},
                            {$this->email},
                            {$this->password},
                            {$this->direccion},
                            {$this->postal},
                            {$this->localidad},
                            {$this->provincia},
                            {$this->pais},
                            {$this->telefono},
                            {$this->celular},
                            {$this->minorista},
                            {$this->invitado},
                            {$this->descuento},
                            {$this->fecha},
                            {$this->estado})";
            $this->con->sql($sql);
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $usuario = $this->view();
        $validar = $this->validate();
        if (is_array($validar)) {
            if ($validar['data']["email"] == $usuario['data']["email"]) {
                if ($usuario['data']["password"] != $this->password) {
                    $this->set("password", hash('sha256', $this->password . SALT));
                }
                $this->transformQuery();
                $sql = "UPDATE `usuarios` 
                        SET `nombre` = {$this->nombre},
                            `apellido` = {$this->apellido},
                            `doc` = {$this->doc},
                            `email` = {$this->email},
                            `password` = {$this->password},
                            `direccion` = {$this->direccion},
                            `postal` = {$this->postal},
                            `localidad` = {$this->localidad},
                            `provincia` = {$this->provincia},
                            `pais` = {$this->pais},
                            `telefono` = {$this->telefono},
                            `celular` = {$this->celular},
                            `invitado` = {$this->invitado},
                            `minorista` = {$this->minorista},
                            `descuento` = {$this->descuento},
                            `estado` = {$this->estado},
                            `fecha` = {$this->fecha}
                        WHERE `cod`={$this->cod}";
                $this->con->sql($sql);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function editSingle($atributo, $valor)
    {
        $validar = $this->validate();
        $usuario = $this->view();
        if ($atributo == 'password') {
            $valor = hash('sha256', $valor . SALT);
        }
        $sql = "UPDATE `usuarios` SET `$atributo` = '{$valor}' WHERE `cod`='{$this->cod}'";
        if (is_array($validar)) {
            if ($validar['data']["email"] == $usuario['data']["email"]) {
                $this->con->sql($sql);
                return true;
            } else {
                return false;
            }
        } else {
            $this->con->sql($sql);
            return true;
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM `usuarios`WHERE `cod`= {$this->cod}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function login()
    {
        $response = NULL;
        $this->set("password", hash('sha256', $this->password . SALT));
        $sql = "SELECT * FROM `usuarios` WHERE `email` = '{$this->email}' AND `password`= '{$this->password}' AND invitado = 0";
        $usuarios = $this->con->sqlReturn($sql);

        if (!empty($usuarios)) {
            $row = mysqli_fetch_assoc($usuarios);
            if (!empty($row)) {
                if ($row["estado"] == 1) {
                    $_SESSION["usuarios"] = array(
                        'cod' => $row['cod'],
                        'nombre' => $row['nombre'],
                        'apellido' => $row['apellido'],
                        'doc' => $row['doc'],
                        'email' => $row['email'],
                        'direccion' => $row['direccion'],
                        'localidad' => $row['localidad'],
                        'provincia' => $row['provincia'],
                        'pais' => $row['pais'],
                        'telefono' => $row['telefono'],
                        'minorista' => $row['minorista'],
                        'descuento' => $row['descuento'],
                        'estado' => $row['estado'],
                        'invitado' => $row['invitado'],
                        'fecha' => $row['fecha']
                    );
                    $response = array("status" => true);
                } else {
                    $response = array("status" => false, "error" => 1);
                }
            } else {
                $response = array("status" => false, "error" => 2); //contraseña o email incorrecto
            }
        } else {
            $response = array("status" => false, "error" => 3); //error inesperado no debe existir en la base
        }
        return $response;
    }

    public function logout()
    {
        $funciones = new PublicFunction();
        unset($_SESSION["usuarios"]);
        $funciones->headerMove(URL);
    }

    public function view()
    {
        $sql = "SELECT * FROM `usuarios` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $usuario = $this->con->sqlReturn($sql);

        if (!empty($usuario)) {
            $row = mysqli_fetch_assoc($usuario);
            $row_ = array("data" => $row);
            return $row_;
        } else {
            return null;
        }
    }

    public function validate()
    {
        if (!empty($this->email)) {
            $sql = "SELECT * FROM `usuarios` WHERE email = '{$this->email}'";
            $usuario = $this->con->sqlReturn($sql);
            $row = mysqli_fetch_assoc($usuario);
            if (!empty($row)) {
                $response = array("status" => true, "data" => $row);
                return $response;
            } else {
                $response = array("status" => false);
                return $response;
            }
        } else {
            $response = array("status" => false);
            return $response;
        }
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

        $sql = "SELECT * FROM `usuarios` $filterSql  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = array("data" => $row);
            }
            return $array;
        } else {
            return false;
        }
    }
    //Sessions
    public function viewSession()
    {
        if (!isset($_SESSION["usuarios"])) {
            $_SESSION["usuarios"] = array();
            return $_SESSION["usuarios"];
        } else {
            return $_SESSION["usuarios"];
        }
    }

    public function firstGuestSession()
    {
        $_SESSION["usuarios"] = array(
            'cod' => $this->cod,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'doc' => $this->doc,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'localidad' => $this->localidad,
            'provincia' => $this->provincia,
            'telefono' => $this->telefono,
            'invitado' => $this->invitado,
            'minorista' => 1,
            'descuento' => 0,
            'fecha' => $this->fecha
        );

        $this->transformQuery();
        $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `minorista`,`invitado`,`descuento`, `fecha`,`estado`) 
                VALUES ({$this->cod},
                        {$this->nombre},
                        {$this->apellido},
                        {$this->doc},
                        {$this->email},
                        {$this->direccion},
                        {$this->postal},
                        {$this->localidad},
                        {$this->provincia},
                        {$this->pais},
                        {$this->telefono},
                        {$this->celular},
                        1,
                        1,
                        0,
                        {$this->fecha},
                        1
                        )";
        $this->con->sql($sql);
    }

    public function guestSession()
    {
        $_SESSION["usuarios"] = array(
            'cod' => $this->cod,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'doc' => $this->doc,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'localidad' => $this->localidad,
            'provincia' => $this->provincia,
            'telefono' => $this->telefono,
            'invitado' => $this->invitado,
            'minorista' => 1,
            'descuento' => 0,
            'fecha' => $this->fecha
        );

        $this->transformQuery();
        $sql = "UPDATE `usuarios` 
                SET `nombre` = {$this->nombre},
                    `apellido` = {$this->apellido},
                    `doc` = {$this->doc},
                    `email` = {$this->email},
                    `direccion` = {$this->direccion},
                    `localidad` = {$this->localidad},
                    `provincia` = {$this->provincia},
                    `telefono` = {$this->telefono},
                WHERE `cod`={$this->cod}";
        $this->con->sql($sql);
    }

    //Metodos admin


    public function refreshSession($cod)
    {
        $this->set("cod", $cod);
        $_SESSION["usuarios"] = $this->view()["data"];
        unset($_SESSION["usuarios"]["password"]);
        return $_SESSION["usuarios"];
    }


    public function userSession()
    {
        $_SESSION["usuarios-ecommerce"] = array('cod' => $this->cod, 'nombre' => $this->nombre, 'apellido' => $this->apellido, 'doc' => $this->doc, 'email' => $this->email, 'direccion' => $this->direccion, 'localidad' => $this->localidad, 'provincia' => $this->provincia, 'telefono' => $this->telefono, 'descuento' => $this->descuento);
    }
}
