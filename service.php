<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", "Servicio Técnico | " . TITULO);
$template->set("description", "Servicio Técnico " . TITULO);
$template->set("keywords", "Servicio Técnico " . TITULO);
$template->themeInit();
$service = new Clases\ServicioTecnico();
$contenido = new Clases\Contenidos();

///Datos
$contenido->set("cod", "0b610fe00d");
$contentData = $contenido->view();
//Clases
$template->themeNav();
?>
<!--================Categories Banner Area =================-->
<section class="solid_banner_area">
    <div class="container">
        <div class="solid_banner_inner navegador">
            <h3>Servicio Técnico</h3>
            <ul>
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li><a href="#">Servicio Técnico</a></li>
            </ul>
        </div>
    </div>
</section>
<!--================End Categories Banner Area =================-->
<div class="section-empty">
    <div class="container content pt-15">
        <div class="row">
            <div class="col-md-12">
                <?=$contentData['data']['contenido'];?>
            </div>
            <div class="col-md-4">
                <label>Buscar por provincia:</label>
                <select class="form-control" onchange="listProvincia($(this).val())">
                    <option selected disabled>Elegir provincia</option>
                    <?php                    foreach ($service->listProvincias() as $provincias) {                        ?>
                        <option value="<?= $provincias['provincia'] ?>"><?= $provincias['provincia'] ?></option>
                        <?php                    }                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Buscar por localidad:</label>
                <select class="form-control" id="ciudad" onchange="listCiudad($(this).val())">

                </select>
            </div>
        </div>
        <div id="listado" class="row"></div>
    </div>
</div>
<?php
$template->themeEnd();
?>
<style>
    .distribuidores {
        height:150px;
    }
</style>
<script>
    listProvincia();

    function listProvincia(provincia) {
        $.ajax({
            url: "<?=URL?>/curl/service/provincia.php",
            type: "POST",
            data: {provincia: provincia},
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data['status']) {
                    $('#listado').html('');
                    $('#listado').append(data['response']);
                    $('#ciudad').html('');
                    $('#ciudad').append(data['ciudad']);
                } else {
                    $('#listado').html('');
                    $('#listado').append("<div class='col-md-3'>" + data['message'] + "</div>");
                }
            }
        });
    }

    function listCiudad(ciudad) {
        $.ajax({
            url: "<?=URL?>/curl/service/ciudad.php",
            type: "POST",
            data: {ciudad: ciudad},
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data['status']) {
                    $('#listado').html('');
                    $('#listado').append(data['response']);
                } else {
                    $('#listado').html('');
                    $('#listado').append("<div class='col-md-3'>" + data['message'] + "</div>");
                }
            }
        });
    }
</script>
