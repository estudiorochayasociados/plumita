<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
//
$template->set("title", TITULO . " | Productos");
$template->set("description", "Plumita S.R.L es una fábrica de Cortadoras y Bordeadoras de cesped, de muy alto nivel con distribución en todo el país.");
$template->set("keywords", "bordeadora, cortadora, cesped, pasto, yuyo, alto nivel, durardera, maquinas, compra");
$template->set("favicon", FAVICON);
$template->themeInit();
//Categorias


//Productos
$pagina = $funciones->antihack_mysqli(isset($_GET["pagina"]) ? $_GET["pagina"] : '0');
$categoria_get = $funciones->antihack_mysqli(isset($_GET["categoria"]) ? $_GET["categoria"] : '');
$titulo = $funciones->antihack_mysqli(isset($_GET["titulo"]) ? $_GET["titulo"] : '');
$orden_pagina = $funciones->antihack_mysqli(isset($_GET["order"]) ? $_GET["order"] : '');
$id = $funciones->antihack_mysqli(isset($_GET["id"]) ? $_GET["id"] : '');
//
$categoriasData = $categoria->listWithOps(["area='productos'"], '', '');

$filter = [];
if (!empty($categoria_get)) {
    foreach ($categoriasData as $cats) {
        $title = $funciones->normalizar_link($cats['titulo']);
        if ($title == $categoria_get) {
            $categoria_get=str_replace("-"," ",$categoria_get);
            $categoria_data_filtro = $categoria->list(["titulo LIKE '%$categoria_get%'"]);
            if (!empty($categoria_data_filtro)) $filter[] = "categoria='" . $categoria_data_filtro[0]['cod'] . "'";
            break;
        }
    }
}

$cantidad = 9;
if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}
if (@count($_GET) >= 1) {
    $anidador = "&";
} else {
    $anidador = "?";
}

if (isset($_GET['pagina'])) {
    $url = $funciones->eliminar_get(CANONICAL, 'pagina');
} else {
    $url = CANONICAL;
}

if (!empty($titulo)) $filter[] = "titulo LIKE '%$titulo%' || desarrollo LIKE '%$titulo%'";

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

if (empty($filter)) $filter = '';
$productos_data = $producto->listWithOps($filter, $order_final, ($cantidad * $pagina) . ',' . $cantidad);
$numeroPaginas = $producto->paginador($filter, $cantidad);
$productos_data_random = $producto->listWithOps('', 'RAND()', '4');
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
    <section class="categories_product_main p_20">
        <div class="container">
            <div class="categories_main_inner">
                <div class="row row_disable">
                    <div class="col-lg-9 float-md-right">
                        <div class="">
                            <div class="row">
                                <div class="col-md-12 mb-10">
                                    <form class="login_form row" method="get" id="buscar">
                                        <div class="col-md-9 form-group">
                                            <input class="form-control" value="<?= isset($titulo) ? $titulo : ''; ?>" type="text" placeholder="Buscar un producto" name="titulo"
                                                   required>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <button type="submit" class="btn update_btn form-control"><i class="icon-magnifier icons"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="categories_product_area">
                            <div class="row">
                                <?php foreach ($productos_data as $prod) { ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="l_product_item">
                                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                                <div class="l_p_img" style="height:300px;background:url(<?= URL . '/' . $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/70%;">
                                                </div>
                                            </a>
                                            <div class="l_p_text">
                                                <ul>
                                                    <li>
                                                        <a class="add_cart_btn" href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                                            Ver
                                                        </a>
                                                    </li>
                                                </ul>
                                                <h4><?= ucfirst(substr(strip_tags($prod['data']['titulo']), 0, 40)); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <nav aria-label="Page navigation example" class="pagination_area mb-15">
                                <?php if ($numeroPaginas > 1): ?>
                                    <div class="col-xs-12">
                                        <div class="pagination mb-60">
                                            <ul class="pagination text-center">
                                                <?php if (($pagina + 1) > 1): ?>
                                                    <li><a href="<?= $url ?><?= $anidador ?>pagina=<?= $pagina ?>"><i
                                                                    class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $numeroPaginas; $i++): ?>
                                                    <li><a href="<?= $url ?><?= $anidador ?>pagina=<?= $i ?>" class="<?php if ($i == $pagina + 1) {
                                                            echo "active";
                                                        } ?>"><?= $i ?></a></li>
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
                                    <?php foreach ($categoriasData as $cats) { ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?= URL . '/productos/c/' .$funciones->normalizar_link($cats['titulo']); ?>">
                                                <?= ucfirst(substr(strip_tags($cats['titulo']), 0, 60)); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </aside>
                            <aside class="l_widgest l_feature_widget">
                                <div class="l_w_title">
                                    <h3>Recomendados</h3>
                                </div>
                                <?php foreach ($productos_data_random as $proRand) { ?>
                                    <div class="media">
                                        <a href="<?= URL . '/producto/' . $funciones->normalizar_link($proRand['data']["titulo"]) . '/' . $proRand['data']['cod'] ?>">
                                            <div class="d-flex" style="height:100px;width:80px;background:url(<?= URL . '/' . $proRand['imagenes']['0']['ruta']; ?>) no-repeat center center/contain;">
                                            </div>
                                        </a>
                                        <div class="media-body ml-5">
                                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($proRand['data']["titulo"]) . '/' . $proRand['data']['cod'] ?>">
                                                <h4><?= ucfirst(substr(strip_tags($proRand['data']['titulo']), 0, 40)); ?></h4>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Categories Product Area =================-->
<?php $template->themeEnd(); ?>