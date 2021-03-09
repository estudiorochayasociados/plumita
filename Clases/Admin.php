<?php

namespace Clases;

class Admin
{

    //Atributos
    public $id;
    public $email;
    public $password;
    public $fecha;
    private $rol;
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
        $sql = "INSERT INTO `admin` ( `email`, `password`) VALUES ('{$this->email}', '{$this->password}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql = "UPDATE `admin` 
                  SET email =  '{$this->email}' ,
                      password =  '{$this->password}'  
                  WHERE `id`= {$this->id} ";
        $this->con->sql($sql);
        return true;
    }

    public function addRolAdmin()
    {
        $sql = "INSERT INTO `roles_admin` (`rol`, `admin`) VALUES ({$this->rol},{$this->id})";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function deleteRolAdmin()
    {
        $sql = "DELETE FROM `roles_admin` WHERE `rol`  = {$this->rol} AND `admin`  = {$this->id}";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `admin` WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $array = array();
        $sql = "SELECT admin.id, admin.email, admin.password, roles_admin.rol FROM admin JOIN roles_admin ON admin.id = roles_admin.admin WHERE admin.id = '{$this->id}'";
        $admin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($admin)) {
            $this->id = $row["id"];
            $this->email = $row["email"];
            $this->password = $row["password"];
            $this->rol[] = $row["rol"];
        }
        $array["data"] = [
            "id" => $this->id,
            "email" => $this->email,
            "password" => $this->password,
            "rol" => $this->rol,
        ];
        return $array;
    }

    public function login()
    {
        $sql = "SELECT admin.id, admin.email, admin.password, roles_admin.rol FROM admin JOIN roles_admin ON admin.id = roles_admin.admin WHERE email = '{$this->email}' AND password = '{$this->password}'";
        $admin = $this->con->sqlReturn($sql);
        $contar = mysqli_num_rows($admin);
        if ($contar > 0) {
            while ($row = mysqli_fetch_assoc($admin)) {
                $this->id = $row["id"];
                $this->email = $row["email"];
                $this->password = $row["password"];
                $this->rol[] = $row["rol"];
            }
            $_SESSION["admin"] = [
                "id" => $this->id,
                "email" => $this->email,
                "password" => $this->password,
                "rol" => $this->rol,
            ];
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $funciones = new PublicFunction();
        unset($_SESSION["admin"]);
        $funciones->headerMove(URL);
    }


    public function loginForm()
    {
        $admin = new Admin();
        $funciones = new PublicFunction();
?>
        <center class="mt-100  align-center text-center">
            <div style="width: 400px;margin: auto">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <img src="<?= LOGO ?>" width="300" class="mb-10" />
                        <h3 class="panel-title text-uppercase">Ingresar al administrador</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <?php
                            if (isset($_POST["login"])) {
                                $admin->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
                                $admin->set("password", isset($_POST["password"]) ? $funciones->antihack_mysqli($_POST["password"]) : '');
                                $adm = $admin->login();
                                if ($adm == true) {
                                    $funciones->headerMove(URLADMIN . "/index.php");
                                } else {
                                    echo "<div class='alert alert-danger'>El usuario no existe o no coincide con la contrasena.</div>";
                                }
                            }
                            ?>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password">
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Ingresar" name="login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </center>
        <?php
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
            $orderSql = "ORDER BY id ASC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }

        $sql = "SELECT * FROM (SELECT admin.id, admin.email, admin.password, roles_admin.rol, roles.nombre FROM admin JOIN roles_admin ON admin.id = roles_admin.admin JOIN roles ON roles_admin.rol = roles.id UNION SELECT *, '', '' FROM admin) as T1 $filterSql $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            $senal = '';
            $i = -1;
            while ($row = mysqli_fetch_assoc($notas)) {
                if ($senal == $row["id"]) {
                    $this->rol[] = $row["rol"];
                    $array[$i] = [
                        "id" => $this->id,
                        "email" => $this->email,
                        "password" => $this->password,
                        "rol" => $this->rol,
                    ];
                } else {
                    $senal = $row["id"];
                    unset($this->rol);
                    $this->id = $row["id"];
                    $this->email = $row["email"];
                    $this->password = $row["password"];
                    $this->rol[] = $row["rol"];
                    $i++;
                }
                $array[$i]["data"] = [
                    "id" => $this->id,
                    "email" => $this->email,
                    "password" => $this->password,
                    "rol" => $this->rol,
                ];
            }
            return $array;
        }
    }

    public function listTable()
    {
        $sql = "SELECT * FROM `admin` ORDER BY id DESC";
        $admin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($admin)) {
        ?>
            <tr>
                <td><?= strtoupper($row["titulo"]) ?></td>
                <td>
                    <a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL ?>/index.php?op=modificarPortfolio&id=<?= $row["id"] ?>">
                        <i class="fa fa-cog"></i>
                    </a>
                    <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL ?>/index.php?op=verPortfolio&borrar=<?= $row["id"] ?>">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
<?php
        }
    }
}
