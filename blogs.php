<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$novedades = new Clases\Novedades();
$imagen = new Clases\Imagenes();
$categoria = new Clases\Categorias();
//
$template->set("title", TITULO . " | Blogs");
$template->set("description", "Blog de ".TITULO);
$template->set("keywords", "Blog de ".TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();

$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';

$cantidad = 4;

if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($_GET) >= 1) {
    $anidador = "&";
} else {
    $anidador = "?";
}

if (isset($_GET['pagina'])):
    $url = $funciones->eliminar_get(CANONICAL, 'pagina');
else:
    $url = CANONICAL;
endif;

$novedades_data = $novedades->listWithOps("", "", $cantidad * $pagina . ',' . $cantidad);
$numeroPaginas = $novedades->paginador("", $cantidad);

$template->themeNav();
?>
<!--================Categories Banner Area =================-->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Blogs</h3>
            <ul>
                <li><a href="<?=URL?>/index">Inicio</a></li>
                <li class="current"><a href="#">Blogs</a></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->

<section class="from_blog_area">
    <div class="container">
        <div class="from_blog_inner">
            <div class="row">
                <?php
                foreach ($novedades_data as $nov) {
                    ?>
                    <div class="col-lg-6 col-sm-6 mb-20">
                        <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                            <div class="from_blog_item" style="height:350px;background:url(<?= $nov['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
                                <div class="f_blog_text">
                                    <?php
                                    if ($nov['data']['categoria'] != '') {
                                        ?>
                                        <h5><?= ucfirst($nov['categorias']['titulo']); ?></h5>
                                        <?php
                                    }
                                    ?>
                                    <p><?= ucfirst(substr(strip_tags($nov['data']['titulo']), 0, 60)); ?>...</p>
                                    <h6><?= $nov['fecha_actual'] ?></h6>
                                </div>
                            </div>
                        </a>
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
</section>

<?php
$template->themeEnd();
?>

