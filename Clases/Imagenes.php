<?php

namespace Clases;

class Imagenes
{

    //Atributos
    public $id;
    public $link;
    public $ruta;
    public $orden;
    public $cod;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->zebra = new Zebra_Image();
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
        $sql = "INSERT INTO `imagenes`(`ruta`, `cod`, `orden`) VALUES ({$this->ruta}, {$this->cod},0)";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function edit()
    {
        $sql = "UPDATE `imagenes` SET ruta = {$this->ruta}, cod = {$this->cod} WHERE `id`={$this->id}";
        echo $sql;
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function editRuta()
    {
        $sql = "UPDATE `imagenes` SET ruta = {$this->ruta} WHERE `cod`={$this->cod}";
        echo $sql;
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function editAllCod($cod)
    {
        $sql = "UPDATE `imagenes` SET cod = {$this->cod} WHERE `cod`='$cod'";
        $query = $this->con->sql($sql);
        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = "SELECT * FROM `imagenes` WHERE id = {$this->id}";
        $imagen = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($imagen)) {
            $sqlDelete = "DELETE FROM `imagenes` WHERE `id` = '" . $row['id'] . "'";
            $query = $this->con->sqlReturn($sqlDelete);
            unlink("../" . $row["ruta"]);
        }
    }

    public function deleteAll()
    {
        $sql = "SELECT * FROM `imagenes` WHERE cod = {$this->cod} ORDER BY cod DESC";
        $imagen = $this->con->sqlReturn($sql);

        while ($row = mysqli_fetch_assoc($imagen)) {
            unlink("../" . $row["ruta"]);
        }

        $sqlDelete = "DELETE FROM `imagenes` WHERE cod = {$this->cod}";
        $query = $this->con->sql($sqlDelete);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function view($cod)
    {
        $sql = "SELECT * FROM `imagenes` WHERE cod = '$cod' ORDER BY id ASC";
        $imagenes = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($imagenes);
        if ($row === NULL) {
            return $row;
        } else {
            return $row;
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

        $sql = "SELECT * FROM `imagenes` $filterSql  ORDER BY $orderSql $limitSql";
        $image = $this->con->sqlReturn($sql);

        if ($image) {
            while ($row = mysqli_fetch_assoc($image)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    // function checkForProduct($variable)
    // {
    //     $url = dirname(__DIR__) . '/assets/archivos/productos/' . $variable . '.jpg';
    //     if (@getimagesize($url)) {
    //         $ruta = 'assets/archivos/productos/' . $variable . '.jpg';
    //     } else {
    //         $variable = str_replace("-", "", $variable);
    //         $variable = str_replace("/", "", $variable);
    //         $url = dirname(__DIR__) . '/assets/archivos/productos/' . $variable . '.jpg';
    //         if (@getimagesize($url)) {
    //             $ruta = 'assets/archivos/productos/' . $variable . '.jpg';   
    //         } else {
    //             $ruta = 'assets/archivos/sin_imagen.jpg';
    //         }
    //     }
    //     $img[] = ['ruta' => $ruta];
    //     return $img;
    // }



    function checkForProduct($cod, $variable)
    {
        $array = array();

        $variable = str_replace("/", "-", $variable);
        $url = dirname(__DIR__) . '/assets/archivos/productos/' . $variable . '.jpg';

        if (@getimagesize($url)) {
            $ruta = 'assets/archivos/productos/' . $variable . '.jpg';
            $array[] = ['ruta' => $ruta];
        }

        $sql = "SELECT * FROM `imagenes` WHERE cod = '$cod' ORDER BY orden ASC";
        $imagenes = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($imagenes)) {
            $array[] = $row;
        }

        if (count($array) == 0) {
            $ruta = 'assets/archivos/sin_imagen.jpg';
            $array[] = ['ruta' => $ruta];
        }

        return $array;
    }

    function listValidation($cod)
    {
        $array = array();
        $sql = "SELECT * FROM `imagenes` WHERE cod = '$cod' ORDER BY id ASC";
        $imagenes = $this->con->sqlReturn($sql);
        if ($imagenes->num_rows == 0) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($imagenes)) {
                $array[] = $row;
            }
            return $array;
        }
    }


    public function setOrder()
    {
        $sql = "UPDATE `imagenes` SET orden = {$this->orden} WHERE id = {$this->id}";
        $query = $this->con->sql($sql);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function resizeImages($cod, $files, $path, $final_path)
    {
        foreach ($files['name'] as $f => $name) {
            $tmpFile = $files["tmp_name"][$f];
            $nameFile = $files["name"][$f];
            $explodeName = explode('.', $nameFile);
            $countPoint = (count($explodeName) - 1);
            $extension = $explodeName[$countPoint];
            $newName = substr(md5(uniqid(rand())), 0, 10) . "." . $extension;
            if ($extension != '') {
                $path_ = $path . "/" . $newName;
                move_uploaded_file($tmpFile, $path_);
                $path_final_ = $final_path . "/a_" . $newName;

                $this->zebra->source_path = $path_;
                $this->zebra->target_path = $path_final_;
                $this->zebra->jpeg_quality = 80;
                $this->zebra->preserve_aspect_ratio = true;
                $this->zebra->enlarge_smaller_images = true;
                $this->zebra->preserve_time = true;

                if ($this->zebra->resize(0, 0, ZEBRA_IMAGE_NOT_BOXED,-1)) {
                    unlink($path_);
                }

                $this->set("cod", $cod);
                $this->set("ruta", str_replace("../", "", $path_final_));
                $this->add();
            }
        }
    }
}
