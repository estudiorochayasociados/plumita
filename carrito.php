<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", TITULO . " | Carrito de compra");
$template->set("description", "Carrito de compra " . TITULO);
$template->set("keywords", "Carrito de compra " . TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$banners = new Clases\Banner();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();

if (count($carro) == 0) {
    $funciones->headerMove(URL . "/productos.php");
}
?>
<?php $template->themeNav(); ?>
    <!--================Categories Banner Area =================-->
    <section class="solid_banner_area">
        <div class="container">
            <div class="solid_banner_inner navegador">
                <h3>Tu carrito</h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="current"><a href="#">Tu carrito</a></li>
                </ul>
            </div>
        </div>
    </section>
    <!--================End Categories Banner Area =================-->
    <section class="shopping_cart_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart_product_list">
                        <div class="envio">
                            <?php
                            $metodos_de_envios = $envios->list(array("peso >= " . $carrito->peso_final() . " OR peso = 0"));
                            if ($carroEnvio == '') {
                                echo "<h3>Seleccioná el envió que más te convenga:</h3>";
                                if (isset($_POST["envio"])) {
                                    if ($carroEnvio != '') {
                                        $carrito->delete($carroEnvio);
                                    }
                                    $envio_final = $_POST["envio"];
                                    $envios->set("cod", $envio_final);
                                    $envio_final_ = $envios->view();
                                    $carrito->set("id", "Envio-Seleccion");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", $envio_final_["titulo"]);
                                    $carrito->set("precio", $envio_final_["precio"]);
                                    $carrito->add();
                                    $funciones->headerMove(CANONICAL . "");
                                }
                                ?>
                                <div class="clearfix"></div>
                                <form method="post" class="calculate_shoping_form" id="envio">
                                    <select name="envio" class="form-control mt-5" id="envio" onchange="this.form.submit()">
                                        <option value="" selected disabled>Elegir envío</option>
                                        <?php
                                        foreach ($metodos_de_envios as $metodos_de_envio_) {
                                            if ($metodos_de_envio_["precio"] == 0) {
                                                $metodos_de_envio_precio = "¡Gratis!";
                                            } else {
                                                $metodos_de_envio_precio = "$" . $metodos_de_envio_["precio"];
                                            }
                                            echo "<option value='" . $metodos_de_envio_["cod"] . "'>" . $metodos_de_envio_["titulo"] . " -> " . $metodos_de_envio_precio . "</option>";
                                        }
                                        ?>
                                    </select>
                                </form>
                                <hr/>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Producto</th>
                                    <th class="hidden-xs" scope="col">Precio</th>
                                    <th class="hidden-xs" scope="col">Cantidad</th>
                                    <th scope="col">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $carroEnvio = $carrito->checkEnvio();
                                if (isset($_GET["remover"])) {
                                    $carrito->delete($_GET["remover"]);
                                    $carroEnvio = $carrito->checkEnvio();
                                    if ($carroEnvio != '') {
                                        $carrito->delete($carroEnvio);
                                    }
                                    $funciones->headerMove(URL . "/carrito");
                                }

                                $i = 0;
                                $precio = 0;
                                foreach ($carro as $key => $carroItem) {

                                    $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                    $opciones = @implode(" - ", $carroItem["opciones"]);
                                    if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                        $clase = "text-bold";
                                        $none = "hidden";
                                    } else {
                                        $clase;
                                        $none = "";
                                    }
                                    $productos->set("id", $carroItem['id']);
                                    $pro = $productos->view();
                                    $imagenes->set("cod", $pro['cod']);
                                    $img = $imagenes->view();
                                    ?>
                                    <tr>
                                        <td scope="row">
                                            <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>">
                                                <img src="<?= URL ?>/assets/img/icon/close-icon.png" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <div class="media hidden-xs">
                                                <div class="d-flex" style="width:70px;height:100px;background:url(<?= URL . '/' . $img['ruta']; ?>) no-repeat center center/contain">
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= mb_strtoupper($carroItem["titulo"]); ?></h4>
                                                </div>
                                            </div>
                                            <div class="d-md-none text-left">
                                                <?= mb_strtoupper($carroItem["titulo"]); ?>
                                                <p class="<?= $none ?>">Precio: <?= "$" . $carroItem["precio"]; ?></p>
                                                <p class="<?= $none ?>">Cantidad: <?= $carroItem["cantidad"]; ?></p>
                                            </div>
                                        </td>
                                        <td class="hidden-xs"><p class="<?= $none ?>"><?= "$" . $carroItem["precio"]; ?></p></td>
                                        <td class="hidden-xs"><p class="<?= $none ?>"><?= $carroItem["cantidad"]; ?></p></td>

                                        <td><p><?php
                                                if ($carroItem["precio"] != 0) {
                                                    echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                                } else {
                                                    echo "¡Gratis!";
                                                }
                                                ?></p></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form class="" method="post">
                        <!---->
                        <?php
                        $metodo = isset($_POST["metodos-pago"]) ? $_POST["metodos-pago"] : '';
                        $metodo_get = isset($_GET["metodos-pago"]) ? $_GET["metodos-pago"] : '';
                        if ($metodo != '') {
                            $key_metodo = $carrito->checkPago();
                            $carrito->delete($key_metodo);
                            $pagos->set("cod", $metodo);
                            $pago__ = $pagos->view();
                            $precio_final_metodo = $carrito->precio_total();
                            if ($pago__["aumento"] != 0 || $pago__["disminuir"] != '') {
                                if ($pago__["aumento"]) {
                                    $numero = (($precio_final_metodo * $pago__["aumento"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "CARGO +" . $pago__['aumento'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", $numero);
                                    $carrito->add();
                                } else {
                                    $numero = (($precio_final_metodo * $pago__["disminuir"]) / 100);
                                    $carrito->set("id", "Metodo-Pago");
                                    $carrito->set("cantidad", 1);
                                    $carrito->set("titulo", "DESCUENTO -" . $pago__['disminuir'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                    $carrito->set("precio", "-" . $numero);
                                    $carrito->add();
                                }
                                $funciones->headerMove(CANONICAL . "/" . $metodo);
                            }
                        }
                        ?>
                        <div class="cart_totals_area">
                            <?php
                            if ($carroEnvio != '') {
                                ?>
                                <h4>Metodos de pago</h4>
                                <?php
                            } elseif ($carroEnvio == '') {
                                ?>
                                <h4>Elegir envío</h4>
                                <?php
                            }
                            ?>
                            <div class="cart_t_list">
                                <div class="media">
                                    <div class="media-body">
                                        <?php if ($carroEnvio == '') { ?>
                                            <span class="btn boton-envio " onclick="$('#envio').addClass('alert alert-danger');">¿CÓMO PEREFERÍS EL ENVÍO DEL PEDIDO?</span>
                                            <p class="checkout text-bold">¡Necesitamos que nos digas como querés realizar <br/>tu envío para que lo tengas listo cuanto antes!</p>
                                            <?php
                                        } else {
                                            $lista_pagos = $pagos->list(array(" estado = 0 "));
                                            foreach ($lista_pagos as $pago) {
                                                ?>
                                                <div class="radioButtonPay mb-10">
                                                    <input type="radio" id="<?= ($pago["cod"]) ?>" name="metodos-pago" value="<?= ($pago["cod"]) ?>" onclick="this.form.submit()" <?php if ($metodo_get === $pago["cod"]) {
                                                        echo " checked ";
                                                    } ?>>
                                                    <label for="<?= ($pago["cod"]) ?>"><b><?= mb_strtoupper($pago["titulo"]) ?></b></label>
                                                    <p>
                                                        <?= $pago["leyenda"] ?>
                                                    </p>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="total_amount row m0 row_disable">
                                <div class="float-left">
                                    Total
                                </div>
                                <div class="float-right">
                                    $<?= number_format($carrito->precio_total(), "2", ",", "."); ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($metodo_get != '') { ?>
                            <a href="<?= URL ?>/pagar/<?= $metodo_get ?>" class="btn boton-pagar form-control">PAGAR EL CARRITO</a>
                        <?php } ?>
                        <!---->
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php
$template->themeEnd();
?>