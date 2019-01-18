<?php
$categoria_footer = new Clases\Categorias();
$banner_footer = new Clases\Banner();
$imagen = new Clases\Imagenes();
//Banners
$categoria_footer->set("area", "banners");
$categorias_banners = $categoria_footer->listForArea('');
foreach ($categorias_banners as $catB) {
    if ($catB['titulo'] == "Pie") {
        $banner_footer->set("categoria", $catB['cod']);
        $banner_data_pie = $banner_footer->listForCategory('RAND()', '1');
    }
}
?>
<section class="">
    <div class="container">
        <div class="row">
            <?php
            foreach ($banner_data_pie as $banP) {
                $imagen->set("cod", $banP['cod']);
                $img = $imagen->view();
                $banner_footer->set("id", $banP['id']);
                $value = $banP['vistas'] + 1;
                $banner_footer->set("vistas", $value);
                $banner_footer->increaseViews();
                ?>
                <div class="col-md-12">
                    <a href="<?= $banP['link']; ?>">
                        <div class="special_offer_item" style="height:150px;background:url(<?= $img['ruta']; ?>) no-repeat center center/contain;">
                        </div>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<!--================Footer Area =================-->
<footer class="footer_area">
    <div class="container">
        <div class="footer_widgets">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-6">
                    <aside class="f_widget f_about_widget">
                        <div class="col-md-12" style="width:100%;height:80px;background:url(<?= LOGO ?>) no-repeat center center/contain;">
                        </div>
                        <p>Fabricante de cortadoras, bordeadoras de céspec y electrobombas</p>
                        <h6>Redes sociales:</h6>
                        <ul>
                            <li><a href="https://www.facebook.com/plumitasrl/"><i class="social_facebook"></i></a></li>
                            <!--
                            <li><a href="#"><i class="social_twitter"></i></a></li>
                            <li><a href="#"><i class="social_pinterest"></i></a></li>
                            <li><a href="#"><i class="social_instagram"></i></a></li>
                            <li><a href="#"><i class="social_youtube"></i></a></li>
                            -->
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <aside class="f_widget link_widget f_info_widget">
                        <div class="f_w_title">
                            <h3>Informacón</h3>
                        </div>
                        <ul>
                            <li><a href="<?= URL ?>/c/empresa">Sobre nosotros</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <aside class="f_widget link_widget f_account_widget">
                        <div class="f_w_title">
                            <h3>Mi cuenta</h3>
                        </div>
                        <ul>
                            <?php if (@count($_SESSION["usuarios"]) != 0): ?>
                                <li>
                                    <a title="cuenta" href="<?= URL ?>/sesion">Mi cuenta
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a data-toggle="modal" data-target="#login"
                                       title="Iniciar sesion">Mi cuenta
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><a href="<?= URL ?>/carrito">Carrito</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-4 col-md-4 col-6">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Ffacebook.com%2Fplumitasrl%2F&tabs&width=340&height=181&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="340" height="181" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
                </div>
            </div>
        </div>
        <div class="footer_copyright">
            <h5>©
                <script>document.write(new Date().getFullYear());</script>
                Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                Todos los derechos reservados, Plumita SRL. Copyright by <a href="http://www.estudiorochayasoc.com" target="_blank">Estudio Rocha & Asociados</a>

            </h5>
        </div>
    </div>
</footer>

<!--================End Footer Area =================-->


<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins)-->
<script src="<?= URL ?>/assets/js/jquery-3.2.1.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= URL ?>/assets/js/popper.min.js"></script>
<script src="<?= URL ?>/assets/js/bootstrap.min.js"></script>
<!-- Rev slider js -->
<script src="<?= URL ?>/vendors/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.video.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="<?= URL ?>/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<!-- Extra plugin css -->
<script src="<?= URL ?>/vendors/counterup/jquery.waypoints.min.js"></script>
<script src="<?= URL ?>/vendors/counterup/jquery.counterup.min.js"></script>
<script src="<?= URL ?>/vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="<?= URL ?>/vendors/bootstrap-selector/js/bootstrap-select.min.js"></script>
<script src="<?= URL ?>/vendors/image-dropdown/jquery.dd.min.js"></script>
<script src="<?= URL ?>/assets/js/smoothscroll.js"></script>
<script src="<?= URL ?>/vendors/isotope/imagesloaded.pkgd.min.js"></script>
<script src="<?= URL ?>/vendors/isotope/isotope.pkgd.min.js"></script>
<script src="<?= URL ?>/vendors/magnify-popup/jquery.magnific-popup.min.js"></script>

<script src="<?= URL ?>/vendors/jquery-ui/jquery-ui.js"></script>
<script src="<?= URL ?>/assets/js/theme.js"></script>

<?php include("login.inc.php"); ?>

</body>
</html>