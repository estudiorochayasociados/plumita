<?php
//
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$categoria = new Clases\Categorias();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();

$categoriasData = $categoria->list(array("area = 'productos'"), "", "");
$cod = substr(md5(uniqid(rand())), 0, 10);

if (isset($_POST["agregar"])) {
    $subcategoria = $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : '');
    $producto = $productos->list(array("subcategoria = '$subcategoria'"), "", "");
    foreach ($producto as $product_) {
        if ($product_['data']['subcategoria'] == $subcategoria) {
            $productos->set("cod", $product_["data"]['cod']);
            $resultado = $product_["data"]['precio'] * $_POST["porcentaje"] / 100 + $product_["data"]['precio'];
            $resultado = number_format($resultado,2,".","");
            $productos->editSingle('precio',$resultado);

            // var_dump($resultado);
        }
    }
    $error = '';

    if (empty($error)) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=productos');
    }
}
?>

<div class="col-md-12">
    <h4>
        Productos
    </h4>
    <hr />
    <?php
    if (!empty($error)) {
    ?>
        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
    <?php
    }
    ?>
    <form method="post" class="row" enctype="multipart/form-data">

        <input type="hidden" name="cod" value="<?= $cod; ?>" />
        <label class="col-md-3">
            Subcategoría:<br />
            <select name="subcategoria">
                <option value="">-- Sin subcategoría --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                ?>
                    <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                        <?php foreach ($categoria["subcategories"] as $subcategorias) { ?>
                            <option name="subcategorias" value="<?= $subcategorias["data"]["cod"] ?>"><?= mb_strtoupper($subcategorias["data"]["titulo"]) ?></option>
                        <?php } ?>
                    </optgroup>
                <?php
                }
                ?>
            </select>
        </label>
        
        <label class="col-md-2">Porcentaje:<br />
            <input data-suffix="%" id="pes" value="<?= isset($_POST["porcentaje"]) ? $_POST["porcentaje"] : 0; ?>" name="porcentaje" type="number" min="-100"/>
        </label>
        <hr>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" id="guardar" name="agregar" value="Modificar Porcentaje de Subcategoria" />
        </div>
    </form>
</div>


<!-- todo: pasar a script -->
<script>
    $("#pes").inputSpinner();

    setInterval(ML, 1000);

    function ML() {
        if ($('#meli').prop('checked') == false && $('#cod_meli').val() == '') {
            $('#cod_meli').attr('disabled', false);
            $('#meli').attr('disabled', false);
            $('#stock').attr('min', 0);
        }
    };


    function checkAttrProducts() {
        $.ajax({
            url: "<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosVer.php",
            type: "GET",
            data: {
                cod: "<?= $cod ?>"
            },
            success: function(data) {
                data = JSON.parse(data);
                $('#listAttr').html('');
                if (data.length != 0) {
                    for (i = 0; i < data.length; i++) {
                        var texto = "<strong>" + data[i]["atribute"]["value"] + ": </strong>";
                        for (o = 0; o < data[i]["atribute"]["subatributes"].length; o++) {
                            texto += data[i]["atribute"]["subatributes"][o]["value"] + " | ";
                        }
                        $('#listAttr').append("<span class='text-uppercase mt-10'>" + texto + "</span>");
                        $('#listAttr').append(
                            "<span class='ml-10  mt-10 mb-5 btn btn-warning btn-sm text-uppercase' onclick='openModal(\"<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosModificar.php?cod=" + data[i]['atribute']['cod'] + "\",\"Modificar " + data[i]['atribute']['value'] + "\")'><i class='fa fa-edit'></i></span><br/>");
                    }

                    $('#variaciones').attr('disabled', false);
                    //si existen atributos mostrar variaciones
                    checkCombProducts();
                } else {
                    $('#variaciones').attr('disabled', true);
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    function checkCombProducts() {
        $.ajax({
            url: "<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesVer.php",
            type: "GET",
            data: {
                cod: "<?= $cod ?>"
            },
            success: function(data) {
                data = JSON.parse(data);
                $('#listComb').html('');
                if (data.length != 0) {
                    for (i = 0; i < data.length; i++) {
                        var texto = "";
                        for (o = 0; o < data[i]["combination"].length; o++) {
                            texto += data[i]["combination"][o]["value"] + " | ";
                        }
                        texto += " <strong>Precio:</strong> $" + data[i]['detail']['precio'] + " <strong>Stock:</strong> " + data[i]['detail']['stock'];
                        if (data[i]['detail']['mayorista'] > 0) {
                            texto += " <strong>Precio Mayorista:</strong> $" + data[i]['detail']['mayorista'];
                        } else {
                            texto += " <strong>Precio Mayorista:</strong> No posee";
                        }
                        $('#listComb').append("<span class='text-uppercase mt-10'>" + texto + "</span>");
                        $('#listComb').append(
                            "<span class='ml-10 mt-10 mb-5 btn btn-warning btn-sm text-uppercase' onclick='openModal(\"<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesModificar.php?cod=" + data[i]['detail']['cod_combinacion'] + "&product=" + data[i]['product'] + "\",\"Modificar " + "\")'><i class='fa fa-edit'></i></span><br/>");
                    }
                }
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    checkCombProducts();
    checkAttrProducts();

    //todo: validar si tiene atributos antes de aplicr una combinacion
</script>