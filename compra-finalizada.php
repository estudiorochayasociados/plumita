<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Compra finalizada");
$template->set("description", "Compra finalizada");
$template->set("keywords", "Compra finalizada");
$template->set("favicon", FAVICON);
$template->themeInit();
$pedidos = new Clases\Pedidos();
$carritos = new Clases\Carrito();
$contenido = new Clases\Contenidos();
$correo = new Clases\Email();
$producto = new Clases\Productos();
$usuarios = new Clases\Usuarios();

$carro = $carritos->return();
$cod_pedido = $_SESSION["cod_pedido"];
$pedidos->set("cod", $cod_pedido);
$pedido_info = $pedidos->info();
if (count($_SESSION["carrito"]) == 0) {
    $funciones->headerMove(URL . "/index");
}

?>
    <section class="solid_banner_area">
        <div class="container">
            <div class="solid_banner_inner navegador">
                <h3>¡Pedido finalizado!</h3>
            </div>
        </div>
    </section>
    <div id="sns_content" class="wrap layout-m">
        <div class="container">
            <div class="ps-404">
                <div class="container">
                    <div class="well well-lg pt-50 pb-50">
                        <h2>
                            CÓDIGO DEL PEDIDO: <span> <?= mb_strtoupper($cod_pedido) ?></span>
                        </h2>
                        <table class="table table-hover text-left">
                            <thead>
                            <th><b>PRODUCTO</b></th>
                            <th class="hidden-xs"><b>CANTIDAD</b></th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($carro as $carroItem) {
                                if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                    $clase = "text-bold";
                                    $none = "hidden";
                                } else {
                                    $clase = '';
                                    $none = '';
                                }
                                ?>
                                <tr class="<?= $clase ?>">
                                    <td>
                                        <div class="media hidden-xs">
                                            <div class="media-body">
                                                <?= mb_strtoupper($carroItem["titulo"]); ?>
                                            </div>
                                        </div>
                                        <div class="d-md-none text-left">
                                            <?= mb_strtoupper($carroItem["titulo"]); ?>
                                            <p class="<?= $none ?>">Cantidad: <?= $carroItem["cantidad"]; ?></p>
                                        </div>
                                    </td>
                                    <td class="hidden-xs"><p class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></p></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$carritos->destroy();
unset($_SESSION["cod_pedido"]);
$template->themeEnd();
?>