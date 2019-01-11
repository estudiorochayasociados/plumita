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
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", LOGO);
$template->themeInit();
//Categorias
$categoria->set("area", "productos");
$categorias_data = $categoria->listForArea('');
//Productos

$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';
$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : '';
$titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : '';
$orden_pagina = isset($_GET["order"]) ? $_GET["order"] : '';

$cantidad = 1;
$filter = array();
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


if ($categoria != '') {
    array_push($filter, " categoria='$categoria' ");
}

if ($titulo != '') {
    $titulo_espacios = strpos($titulo, " ");
    if ($titulo_espacios) {
        $filter_title = array();
        $titulo_explode = explode(" ", $titulo);
        foreach ($titulo_explode as $titulo_) {
            array_push($filter_title, "(titulo LIKE '%$titulo_%'  || descripcion LIKE '%$titulo_%')");
        }
        $filter_title_implode = implode(" OR ", $filter_title);
        array_push($filter, "(" . $filter_title_implode . ")");
    } else {
        array_push($filter, "(titulo LIKE '%$titulo%' || descripcion LIKE '%$titulo%')");
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
echo $order_final;
$productos_data = $producto->listWithOps($filter, $order_final, ($cantidad * $pagina) . ',' . $cantidad);
$numeroPaginas = $producto->paginador($filter, $cantidad);
$productos_data_random = $producto->listWithOps('', 'RAND()', '4');
//
?>
<?php
$template->themeNav();
?>
    <!--================Categories Banner Area =================-->
    <section class="categories_banner_area">
        <div class="container">
            <div class="c_banner_inner">
                <h3>Todos los productos</h3>
                <ul>
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="current"><a href="#">Listado</a></li>
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
                        <div class="showing_fillter">
                            <div class="row m0">
                                <div class="first_fillter">
                                </div>
                                <div class="secand_fillter">
                                    <h4>SORT BY :</h4>
                                    <select class="selectpicker">
                                        <option>Name</option>
                                        <option>Name 2</option>
                                        <option>Name 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="categories_product_area">
                            <div class="row">
                                <?php
                                foreach ($productos_data as $prod) {
                                    $imagen->set("cod",$prod['cod']);
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
                                                <h5>
                                                    $ <?= $prod['precio']; ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <nav aria-label="Page navigation example" class="pagination_area">
                                <ul class="pagination">
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                                    <li class="page-item next"><a class="page-link" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                </ul>
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
                                            <a class="nav-link" href="#"><?= ucfirst(substr(strip_tags($cats['titulo']), 0, 60)); ?>
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
                                        <div class="d-flex" style="height:100px;width:80px;background:url(<?= $img['ruta']; ?>) no-repeat center center/contain;">
                                        </div>
                                        <div class="media-body ml-5">
                                            <h4><?= ucfirst(substr(strip_tags($proRand['titulo']), 0, 40)); ?></h4>
                                            <h5>$<?= $proRand['precio']; ?></h5>
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