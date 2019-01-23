<?php
$usuarios = new Clases\Usuarios();
$funciones=new Clases\PublicFunction();
if (isset($_POST["agregar"])) {
    $cod = substr(md5(uniqid(rand())), 0, 10);

    $usuarios->set("cod", $cod);
    $usuarios->set("nombre", $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : ''));
    $usuarios->set("apellido", $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : ''));
    $usuarios->set("doc", $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : ''));
    $usuarios->set("email", $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : ''));
    $usuarios->set("password", $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : ''));
    $usuarios->set("postal", $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : ''));
    $usuarios->set("localidad", $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : ''));
    $usuarios->set("provincia", $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : ''));
    $usuarios->set("pais", $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : ''));
    $usuarios->set("telefono", $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : ''));
    $usuarios->set("celular", $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : ''));
    $usuarios->set("invitado", $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : '0'));
    $usuarios->set("descuento", $funciones->antihack_mysqli(isset($_POST["descuento"]) ? $_POST["descuento"] : ''));
    $usuarios->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));

    if ($usuarios->add()==1){

    }else{

    }

    //$funciones->headerMove(URL . "/index.php?op=usuarios");
}
?>
