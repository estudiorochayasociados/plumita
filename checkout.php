<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Cierre de compra");
$template->set("description", "Cierre de compra");
$template->set("keywords", "Cierre de compra");
$template->set("favicon", FAVICON);
$template->themeInit();

if (empty($_SESSION['carrito'])) $funciones->headerMove(URL . '/armar-pedido');
if (empty($_SESSION['usuarios'])) $funciones->headerMove(URL);

$carrito = new Clases\Carrito();
$pedidos = new Clases\Pedidos();
$usuarios = new Clases\Usuarios();
$carritos = new Clases\Carrito();
$correo = new Clases\Email;

$template->themeNav();
?>
<!--================Categories Banner Area =================-->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>PEDIDO N°: <?= $_SESSION['cod_pedido'] ?></h3>
            <ul>
                <li>Llená el siguiente formulario para poder finalizar tu pedido <span class="em em---1"></span></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->
<div class="container mt-30">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_POST["registrarmeBtn"])) {
                $cod = substr(md5(uniqid(rand())), 0, 10);
                $codigoCliente = $funciones->antihack_mysqli(isset($_POST["cod-cliente"]) ? $_POST["cod-cliente"] : '');
                $razon = $funciones->antihack_mysqli(isset($_POST["razon"]) ? $_POST["razon"] : '');
                $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');

                if (!empty($codigoCliente) && !empty($razon) && !empty($email)) {
                    $pedidos->set("cod",$_SESSION['cod_pedido']);
                    $pedidoData = $pedidos->view();
                    if (!empty($pedidoData)) $pedidos->delete();

                    $timezone = -3;
                    $fecha = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));
                    $carro = $carritos->return();
                    foreach ($carro as $carroItem) {
                        $pedidos->set("producto", $carroItem["titulo"]);
                        $pedidos->set("cantidad", $carroItem["cantidad"]);
                        $pedidos->set("precio", $carroItem["precio"]);
                        $pedidos->set("estado", 0);
                        $pedidos->set("tipo", "PEDIDO");
                        $pedidos->set("usuario", $_SESSION['usuarios']['cod']);
                        $pedidos->set("detalle", "");
                        $pedidos->set("fecha", $fecha);
                        $pedidos->add();
                    }

                    //MENSAJE = ARMADO CARRITO
                    $mensaje_carro = '<table border="1" style="text-align:left;width:100%;font-size:13px !important"><thead><th>Nombre producto</th><th>Cantidad</th></thead>';
                    foreach ($carro as $carroItemEmail) {
                        $mensaje_carro .= '<tr><td>' . $carroItemEmail["titulo"] . '</td><td>' . $carroItemEmail["cantidad"] . '</td></tr>';
                    }
                    $mensaje_carro .= '</table>';

                    //MENSAJE = DATOS USUARIO COMPRADOR
                    $datos_usuario = "<b>CÓDIGO DE CLIENTE:</b> " . $codigoCliente . "<br/>";
                    $datos_usuario .= "<b>RAZÓN SOCIAL:</b> " . $razon . "<br/>";
                    $datos_usuario .= "<b>EMAIL PARA CONFIRMACIÓN:</b> " . $email . "<br/>";
                    //ADMIN EMAIL
                    $mensajeCompra = '¡Nuevo pedido desde la web!<br/>A continuación te dejamos el detalle del pedido.<hr/> <h3>Pedido realizado:</h3>';
                    $mensajeCompra .= $mensaje_carro;
                    $mensajeCompra .= '<br/><hr/>';
                    $mensajeCompra .= '<h3>Datos de usuario:</h3>';
                    $mensajeCompra .= $datos_usuario;

                    $correo->set("asunto", "NUEVO PEDIDO ONLINE");
                    $correo->set("receptor", EMAIL);
                    $correo->set("emisor", EMAIL);
                    $correo->set("mensaje", $mensajeCompra);
                    $correo->emailEnviar();

                    $funciones->headerMove(URL . "/compra-finalizada");
                } else {
                    echo "<div class='alert alert-danger'>Completar los campos correctamente.</div>";
                }
            }
            ?>
            <form method="post" class="row">
                <div class="col-md-6">
                    <h3>Datos</h3>
                    <div class="row">
                        <input type="hidden" name="cod" value="<?= $_SESSION['cod_pedido'] ?>">
                        <div class="col-md-12">CÓDIGO DE CLIENTE:<br/>
                            <input class="form-control  mb-10" type="text" placeholder="Escribir código de cliente" name="cod-cliente" required/>
                        </div>
                        <div class="col-md-12">RAZÓN SOCIAL:<br/>
                            <input class="form-control  mb-10" type="text" placeholder="Escribir razón social" name="razon" required/>
                        </div>
                        <div class="col-md-12">EMAIL PARA CONFIRMACIÓN:<br/>
                            <input class="form-control  mb-10" type="email" placeholder="Escribir email para confirmación" name="email" required/>
                        </div>
                        <div class="col-md-12 col-xs-12 mb-50">
                            <input class="btn btn-success" type="submit" value="¡Finalizar el pedido!" name="registrarmeBtn"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="cartTable" class="mt-10">

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $template->themeEnd(); ?>
<script>
    refreshCart();

    function refreshCart() {
        $.ajax({
            url: "<?=URL?>/curl/cart/view.php",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    $('#cartTable').html('');
                    $('#cartTable').html(data['texto']);
                } else {
                    //$('#cartTable').html('');
                    //$('#cartTable').html(data['texto']);
                }
            },
            error: function () {
            }
        });
    }
</script>
