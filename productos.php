<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$imagen = new Clases\Imagenes();
$novedad = new Clases\Novedades();
$banner = new Clases\Banner();
$slider = new Clases\Sliders();
//
$template->set("title", TITULO . " | Productos");
$template->set("description", "Plumita S.R.L es una fábrica de Cortadoras y Bordeadoras de cesped, de muy alto nivel con distribución en todo el país.");
$template->set("keywords", "bordeadora, cortadora, cesped, pasto, yuyo, alto nivel, durardera, maquinas, compra");
$template->set("favicon", LOGO);
$template->themeInit();
//Categorias
$categoria->set("area", "productos");
$categorias_data = $categoria->listForArea('');
//Productos
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';
$categoria_get = isset($_GET["categoria"]) ? $_GET["categoria"] : '';
$titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : '';
$orden_pagina = isset($_GET["order"]) ? $_GET["order"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
//
$cantidad = 12;
if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}
if (@count($_GET) > 1) {
    $anidador = "&";
} else {
    $anidador = "?";
}

if (isset($_GET['pagina'])):
    $url = $funciones->eliminar_get(CANONICAL, 'pagina');
else:
    $url = CANONICAL;
endif;
//
$filter;
if (!empty($categoria_get)){
    $categoria->set("id",$id);
    $categoria_data_filtro=$categoria->view();
    $cod=$categoria_data_filtro['cod'];
    $filter=array("categoria='$cod'");
}
if ($titulo != '') {
    $titulo_espacios = strpos($titulo, " ");
    if ($titulo_espacios) {
        $filter_title = array();
        $titulo_explode = explode(" ", $titulo);
        foreach ($titulo_explode as $titulo_) {
            array_push($filter_title, "(titulo LIKE '%$titulo_%'  || desarrollo LIKE '%$titulo_%')");
        }
        $filter_title_implode = implode(" OR ", $filter_title);
        array_push($filter, "(" . $filter_title_implode . ")");
    } else {
        $filter=array( "(titulo LIKE '%$titulo%' || desarrollo LIKE '%$titulo%')");
    }
}

switch ($orden_pagina) {
    case "mayor":
        $order_final = "precio DESC";
        break;
    case "menor":
        $order_final = "precio ASC";
        break;
    case "ultimos":
        $order_final = "id DESC";
        break;
    default:
        $order_final = "id DESC";
        break;
}
$productos_data = $producto->listWithOps($filter, $order_final, ($cantidad * $pagina) . ',' . $cantidad);
$numeroPaginas = $producto->paginador($filter, $cantidad);
$productos_data_random = $producto->listWithOps('', 'RAND()', '4');
$sesionCount = @count($_SESSION['usuarios']);
//

$template->themeNav();
?>
    <!--================Categories Banner Area =================-->
    <section class="solid_banner_area">
        <div class="container">
            <div class="solid_banner_inner navegador">
                <h3>Todos los productos</h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="current"><a href="<?= URL ?>/productos">Productos</a></li>
                </ul>
            </div>
        </div>
    </section>
    <!--================End Categories Banner Area =================-->

    <!--================Categories Product Area =================-->
    <section class="categories_product_main p_80">
        <div class="container">
            <div class="categories_main_inner">
                <div class="row row_disable">
                    <div class="col-lg-9 float-md-right">
                        <div class="">
                            <div class="row">
                                <div class="col-md-12 mb-10">
                                    <form class="login_form row"  method="get" id="buscar">
                                        <div class="col-md-9 form-group">
                                            <input class="form-control" value="<?= isset($titulo) ? $titulo : ''; ?>" type="text" placeholder="Buscar un producto" name="titulo"
                                                   required>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <button type="submit" class="btn update_btn form-control"><i class="icon-magnifier icons"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <!--
                                <div class="col-md-6 ordenador">
                                    <h4>Ordenar por :</h4>
                                    <form method="get" class="pull-right">
                                        <?php
                                        foreach ($_GET as $key => $value) {
                                            if ($key != "order" && $key != "pagina") {
                                                echo "<input type='hidden' name='" . $key . "' value='" . $value . "' />";
                                            }
                                        }
                                        ?>
                                        <select class="form-control" name="order"  onchange="this.form.submit()">
                                            <option selected disabled></option>
                                            <option value="ultimos" <?php if ($orden_pagina == "ultimos") {
                                                echo "selected";
                                            } ?>> Últimos
                                            </option>
                                            <option value="mayor" <?php if ($orden_pagina == "mayor") {
                                                echo "selected";
                                            } ?>> Mayor precio
                                            </option>
                                            <option value="menor" <?php if ($orden_pagina == "menor") {
                                                echo "selected";
                                            } ?>> Menor precio
                                            </option>
                                        </select>
                                    </form>
                                </div>-->
                                <!--
                                <div class="third_fillter">
                                    <h4>Show : </h4>
                                    <select class="selectpicker">
                                        <option>09</option>
                                        <option>10</option>
                                        <option>10</option>
                                    </select>
                                </div>
                                <div class="four_fillter">
                                    <h4>View</h4>
                                    <a class="active" href="#"><i class="icon_grid-2x2"></i></a>
                                    <a href="#"><i class="icon_grid-3x3"></i></a>
                                </div>-->
                            </div>
                        </div>
                        <div class="categories_product_area">
                            <div class="row">
                                <?php
                                foreach ($productos_data as $prod) {
                                    $imagen->set("cod", $prod['cod']);
                                    $img = $imagen->view();
                                    ?>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="l_product_item">
                                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod["titulo"]) . '/' . $prod['cod'] ?>">
                                                <div class="l_p_img" style="height:300px;background:url(<?= $img['ruta']; ?>) no-repeat center center/70%;">
                                                </div>
                                            </a>
                                            <div class="l_p_text">
                                                <ul>
                                                    <li>
                                                        <a class="add_cart_btn" href="<?= URL . '/producto/' . $funciones->normalizar_link($prod["titulo"]) . '/' . $prod['cod'] ?>">
                                                            Ver
                                                        </a>
                                                    </li>
                                                </ul>
                                                <h4><?= ucfirst(substr(strip_tags($prod['titulo']), 0, 40)); ?></h4>
                                                <?php
                                                if ($_SESSION["usuarios"]["descuento"] == 1) {
                                                    if ($prod['precio_mayorista'] != 0) {
                                                        ?>
                                                        <h5 class="precios">
                                                            $ <?= $prod['precio_mayorista']; ?>
                                                        </h5>
                                                        <h5 class="precios precio-desc">
                                                            $ <?= $prod['precio']; ?>
                                                        </h5>
                                                        <?php
                                                    } else {
                                                        if ($prod['precio_descuento'] != 0) {
                                                            ?>
                                                            <h5 class="precios">
                                                                $ <?= $prod['precio_descuento']; ?>
                                                            </h5>
                                                            <h5 class="precios precio-desc">
                                                                $ <?= $prod['precio']; ?>
                                                            </h5>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <h5>
                                                                $ <?= $prod['precio']; ?>
                                                            </h5>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    if ($prod['precio_descuento'] != 0) {
                                                        ?>
                                                        <h5 class="precios">
                                                            $ <?= $prod['precio_descuento']; ?>
                                                        </h5>
                                                        <h5 class="precios precio-desc">
                                                            $ <?= $prod['precio']; ?>
                                                        </h5>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <h5>
                                                            $ <?= $prod['precio']; ?>
                                                        </h5>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <nav aria-label="Page navigation example" class="pagination_area">
                                <?php if ($numeroPaginas > 1): ?>
                                    <div class="col-xs-12">
                                        <div class="pagination mb-60">
                                            <ul class="pagination text-center">
                                                <?php if (($pagina + 1) > 1): ?>
                                                    <li><a href="<?= $url ?><?= $anidador ?>pagina=<?= $pagina ?>"><i
                                                                    class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $numeroPaginas; $i++): ?>
                                                    <li class="<?php if ($i == $pagina + 1) {
                                                        echo "active";
                                                    } ?>"><a href="<?= $url ?><?= $anidador ?>pagina=<?= $i ?>"><?= $i ?></a></li>
                                                <?php endfor; ?>

                                                <?php if (($pagina + 2) <= $numeroPaginas): ?>
                                                    <li><a href="<?= $url ?><?= $anidador ?>pagina=<?= ($pagina + 2) ?>"><i
                                                                    class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-3 float-md-right">
                        <div class="categories_sidebar">
                            <aside class="l_widgest l_p_categories_widget">
                                <div class="l_w_title">
                                    <h3>Categorías</h3>
                                </div>
                                <ul class="navbar-nav">
                                    <?php
                                    foreach ($categorias_data as $cats) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?=URL.'/productos?categoria='.strtolower($funciones->normalizar_link($cats['titulo'])).'&id='.$cats['id'];?>"><?= ucfirst(substr(strip_tags($cats['titulo']), 0, 60)); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </aside>
                            <aside class="l_widgest l_feature_widget">
                                <div class="l_w_title">
                                    <h3>Recomendados</h3>
                                </div>
                                <?php
                                foreach ($productos_data_random as $proRand) {
                                    $imagen->set("cod", $proRand['cod']);
                                    $img = $imagen->view();
                                    ?>
                                    <div class="media">
                                        <a href="<?= URL . '/producto/' . $funciones->normalizar_link($proRand["titulo"]) . '/' . $proRand['cod'] ?>">
                                        <div class="d-flex" style="height:100px;width:80px;background:url(<?= $img['ruta']; ?>) no-repeat center center/contain;">
                                        </div>
                                        </a>
                                        <div class="media-body ml-5">
                                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($proRand["titulo"]) . '/' . $proRand['cod'] ?>">
                                                <h4><?= ucfirst(substr(strip_tags($proRand['titulo']), 0, 40)); ?></h4>
                                            </a>
                                            <?php
                                            if ($_SESSION["usuarios"]["descuento"] == 1) {
                                                if (){
                                                if ($proRand['precio_mayorista'] != 0) {
                                                    ?>
                                                    <h5 class="precios">
                                                        $ <?= $proRand['precio_mayorista']; ?>
                                                    </h5>
                                                    <h5 class="precios precio-desc">
                                                        $ <?= $proRand['precio']; ?>
                                                    </h5>
                                                    <?php
                                                } else {
                                                    if ($proRand['precio_descuento'] != 0) {
                                                        ?>
                                                        <h5 class="precios">
                                                            $ <?= $proRand['precio_descuento']; ?>
                                                        </h5>
                                                        <h5 class="precios precio-desc">
                                                            $ <?= $proRand['precio']; ?>
                                                        </h5>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <h5>
                                                            $ <?= $proRand['precio']; ?>
                                                        </h5>
                                                        <?php
                                                    }
                                                }
                                                }
                                            } else {
                                                if ($proRand['precio_descuento'] != 0) {
                                                    ?>
                                                    <h5 class="precios">
                                                        $ <?= $proRand['precio_descuento']; ?>
                                                    </h5>
                                                    <h5 class="precios precio-desc">
                                                        $ <?= $proRand['precio']; ?>
                                                    </h5>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <h5>
                                                        $ <?= $proRand['precio']; ?>
                                                    </h5>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Categories Product Area =================-->
<?php
$template->themeEnd(); ?>