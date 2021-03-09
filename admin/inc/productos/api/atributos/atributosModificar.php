<?php
//le cambie el nombre porque se pisaba con el post en productos
require_once (dirname(__DIR__,5)."/Config/Autoload.php");
Config\Autoload::run();
$config = new Clases\Config();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();

if (isset($_POST["nombre_atributo"])) {
    $nombre_atributo = isset($_POST["nombre_atributo"]) ? $_POST["nombre_atributo"] : '';
    $cod_atributo = isset($_POST["cod_atributo"]) ? $_POST["cod_atributo"] : '';
    $atributo_anterior = isset($_POST["atributo_anterior"]) ? $_POST["atributo_anterior"] : '';
    $atributos = isset($_POST["atributo"]) ? $_POST["atributo"] : '';

    $atributo->set("cod", $cod_atributo);
    $attr = $atributo->view();


    if ($nombre_atributo != $attr["atribute"]["value"]) {
        echo $nombre_atributo;
        $atributo->set("cod", $cod_atributo);
        $atributo->set("value", $nombre_atributo);
        $atributo->edit();
    }


    foreach ($atributo_anterior as $attrCod => $attrValue) {
        $subatributo->set("cod", $attrCod);
        $subatributo_get = $subatributo->view();
        if ($subatributo_get["atribute"]["value"] != $attrValue) {
            $subatributo->set("value", $attrValue);
            $subatributo_get = $subatributo->edit();
        }
    }

    if (!empty($atributos)) {
        foreach ($atributos as $atributos_) {
            echo $atributos_;
            $codSubatributo = substr(md5(uniqid(rand())), 0, 10);
            $subatributo->set("cod", $codSubatributo);
            $subatributo->set("codAtributo", $cod_atributo);
            $subatributo->set("value", $atributos_);
            $subatributo->add();
        }
    }
} else {
    $cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
    $atributo->set("cod", $cod);
    $atributos = $atributo->view();
    $subatributo->set('codAtributo', $cod);
    $subatributos = $subatributo->list();
    ?>
    <div id="resultado"></div>
    <div class="row">
        <form method="post" class="col-md-12" id="form-modal" action="<?= "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
            <div class="col-md-12">
                Nombre del atributo:
                <input type="hidden" name="cod_atributo" value="<?= $atributos["atribute"]["cod"] ?>" data-validation="required"/>
                <input type="text" name="nombre_atributo" value="<?= $atributos["atribute"]["value"] ?>" data-validation="required"/>
                <button type="button" class="ml-10 mb-5 btn btn-info" onclick="agregar_atributo('atributos')"> +</button>
            </div>
            <div class="col-md-12">
                <?php
                foreach ($subatributos as $subatributos_) {
                    ?>
                    <div class="input-group" id="<?= $subatributos_["cod"] ?>">
                        <input onkeydown="return (event.keyCode!=13);" type="text" class="form-control pull-left mb-10 mr-10" name="atributo_anterior[<?= $subatributos_["cod"] ?>]" value="<?= $subatributos_["value"] ?>"/>
                        <div class="pull-right"><a onclick="deleteAttr('subattr','<?= $subatributos_["cod"] ?>')" class="btn btn-primary"> <i class="fas fa-minus"></i></a></div>
                    </div>
                    <!-- return confirm before delete the attr, and then run an ajax function to do the deletetion -->
                    <?php
                }
                ?>
            </div>
            <div class="col-md-12" id="atributos"></div>
            <input type="hidden" name="cod" value="<?= $_GET["cod"] ?>"/>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <a href="#" onclick="deleteAttr('attr','<?= $atributos["atribute"]["cod"] ?>')" class="pull-left btn btn-sm btn-warning">Borrar Atributo</a>
                <input type="submit" class="btn btn-primary btn-sm pull-right" id="guardar" name="agregar-atri" value="Guardar Atributos"/>
            </div>
            <br/>
        </form>
    </div>
    <script>
        $(function () {
            $('#form-modal').on('submit', function (e) {
                $.ajax({
                    type: $('#form-modal').attr('method'),
                    url: $('#form-modal').attr('action'),
                    data: $('#form-modal').serialize(),
                    beforeSend: function () {
                        $("#resultado").html("CARGANDO");
                    },
                    success: function (html) {
                        checkAttrProducts();
                        checkCombProducts();
                        $('#moda-page-ajax').modal('toggle');
                    }
                });
                e.preventDefault();
            });
        });

        function deleteAttr(type, cod) {
            var result = confirm("¿Seguro avanzar con ésta acción?");
            var url = "<?=URLADMIN?>/inc/productos/api/atributos/atributosBorrar.php?" + type + "=" + cod;
            if (result) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (html) {
                        $('#' + cod).remove();
                        checkAttrProducts();
                        checkCombProducts();
                        $('#moda-page-ajax').modal('toggle');
                    }
                });
            }
        }

    </script>
    <?php
}
?>