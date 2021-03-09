<?php
require '../vendor/autoload.php';
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();
$categoria = new Clases\Categorias();
$atributo = new Clases\Atributos();
$subatributo = new Clases\Subatributos();
$combinacion = new Clases\Combinaciones();
$detalleCombinacion = new Clases\DetalleCombinaciones();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarMeli = $funciones->antihack_mysqli(isset($_GET["borrarMeli"]) ? $_GET["borrarMeli"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');
$categoriasData = $categoria->list(array("area = 'productos'"), "titulo ASC", "");

$productos->set("cod", $cod);
$producto = $productos->view(null, true);
$atributo->set("productoCod", $producto['data']['cod']);
$atributosArray = $atributo->list();

//CAMBIAR ORDEN DE LAS IMAGENES
if (isset($_GET["ordenImg"]) && isset($_GET["idImg"])) {
    $imagenes->set("id", $_GET["idImg"]);
    $imagenes->set("orden", $_GET["ordenImg"]);
    $imagenes->setOrder();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=modificar&cod=$cod");
}

//BORRAR IMAGEN
if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=modificar&cod=$cod");
}

//Borrar meli cod
if ($borrarMeli != '') {
    $productos->editSingle("meli", "null");
    $funciones->headerMove(URL_ADMIN . "/index.php?op=productos&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {

    $count = 0;
    $cod = $producto["data"]["cod"];
    $productos->set("id", $producto["data"]["id"]);
    $productos->set("cod", $cod);
    $productos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $productos->set("cod_producto", $funciones->antihack_mysqli(isset($_POST["cod_producto"]) ? $_POST["cod_producto"] : ''));
    $productos->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($_POST["precio_descuento"]) ? $_POST["precio_descuento"] : ''));
    $productos->set("precio_mayorista", $funciones->antihack_mysqli(isset($_POST["precio_mayorista"]) ? $_POST["precio_mayorista"] : ''));
    $productos->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : 0));
    $stock = $funciones->antihack_mysqli(isset($_POST["stock"]) ? $_POST["stock"] : 0);
    $productos->set("stock", $stock);
    $productos->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $productos->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $productos->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $productos->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));
    $productos->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $productos->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));
    $productos->set("meli", $funciones->antihack_mysqli(isset($_POST["meli"]) ? $_POST["meli"] : ''));
    $productos->set("url", $funciones->antihack_mysqli(isset($_POST["url"]) ? $_POST["url"] : ''));

    $cod_meli = $funciones->antihack_mysqli(isset($_POST["cod_meli"]) ? $_POST["cod_meli"] : null);



    $productos->set("variable1", $funciones->antihack_mysqli($_POST["variable1"]));
    $productos->set("variable2", $funciones->antihack_mysqli($_POST["variable2"]));
    $productos->set("variable3", $funciones->antihack_mysqli($_POST["variable3"]));
    $productos->set("variable4", $funciones->antihack_mysqli($_POST["variable4"]));
    $productos->set("variable5", $funciones->antihack_mysqli($_POST["variable5"]));
    $productos->set("variable6", $funciones->antihack_mysqli($_POST["variable6"]));
    $productos->set("variable7", $funciones->antihack_mysqli($_POST["variable7"]));
    $productos->set("variable8", $funciones->antihack_mysqli($_POST["variable8"]));
    $estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
    $productos->set("variable9", $estado);
    $envioGratis = isset($_POST["envioGratis"]) ? $_POST["envioGratis"] : '';
    $productos->set("variable10", $envioGratis);


    $atributos = isset($_POST["atributos"]) ? $_POST["atributos"] : '';

    // IMAGENES
    if (isset($_FILES['files'])) {
        $imagenes->resizeImages($cod, $_FILES['files'], "../assets/archivos", "../assets/archivos/recortadas");
    }

    $error = '';

    // ATRIBUTOS PRODUCTO
    if (empty($error)) {
        $productos->edit();
        $funciones->headerMove(URL_ADMIN . '/index.php?op=productos');
    }
}
?>
<div class="col-md-12 ">
    <h4>Productos</h4>
    <hr />
    <?php
    if (!empty($error)) {
    ?>
        <div class="alert alert-danger" role="alert"><?= $error; ?></div>
    <?php
    }
    ?>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título:<br />
            <input type="text" name="titulo" value="<?= $producto["data"]["titulo"] ?>">
        </label>
        <label class="col-md-3">
            Categoría:<br />
            <select name="categoria">
                <option value="">-- categorías --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                    if ($producto["data"]["categoria"] == $categoria["data"]["cod"]) {
                        echo "<option value='" . $categoria["data"]["cod"] . "' selected>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                    } else {
                        echo "<option value='" . $categoria["data"]["cod"] . "'>" . mb_strtoupper($categoria["data"]["titulo"]) . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-3">
            Subcategoría:<br />
            <select name="subcategoria">
                <option value="">-- Sin subcategoría --</option>
                <?php
                foreach ($categoriasData as $categoria) {
                ?>
                    <optgroup label="<?= mb_strtoupper($categoria["data"]['titulo']) ?>">
                        <?php
                        foreach ($categoria["subcategories"] as $subcategorias) {
                            if ($producto["data"]["subcategoria"] == $subcategorias["data"]["cod"]) {
                                echo "<option value='" . $subcategorias["data"]["cod"] . "' selected>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                            } else {
                                echo "<option value='" . $subcategorias["data"]["cod"] . "'>" . mb_strtoupper($subcategorias["data"]["titulo"]) . "</option>";
                            }
                        }
                        ?>
                    </optgroup>
                <?php
                }
                ?>
            </select>
        </label>
        <label class="col-md-2">Código:<br />
            <input type="text" name="cod_producto" value="<?= $producto["data"]["cod_producto"] ?>">
        </label>
        <label class="col-md-3">Precio:<br />
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" step="any" class="form-control" min="0" value="<?= $producto["data"]["precio"] ? $producto["data"]["precio"] : '0' ?>" name="precio" required>
            </div>
        </label>
        <label class="col-md-3">Precio descuento:<br />
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" step="any" class="form-control" min="0" value="<?= $producto["data"]["precio_descuento"] ? $producto["data"]["precio_descuento"] : '' ?>" name="precio_descuento">
            </div>
        </label>
        <label class="col-md-3">Precio mayorista:<br />
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" step="any" class="form-control" min="0" value="<?= $producto["data"]["precio_mayorista"] ? $producto["data"]["precio_mayorista"] : '' ?>" name="precio_mayorista">
            </div>
        </label>
        <label class="col-md-2">Peso:<br />
            <input data-suffix="Kg" id="pes" value="<?= $producto["data"]["peso"] ?>" min="0" name="peso" type="number" />
        </label>
        <label class="col-md-1">Stock:<br />
            <input type="number" name="stock" id="stock" min="0" value="<?= $producto["data"]["stock"] ? $producto["data"]["stock"] : '0' ?>" required>
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <hr />
            <p class="bold">Los datos variables sirven para agregar más opciones a los productos, son variables del producto:</p>
            <div class="row">
                <?php for ($i = 1; $i <= 8; $i++) { ?>
                    <label class="col-md-2">DATO VARIABLE <?= $i ?>:<br />
                        <input type="text" name="variable<?= $i ?>" value="<?= isset($producto["data"]["variable$i"]) ? $producto["data"]["variable$i"] : ''; ?>">
                    </label>
                <?php } ?>
            </div>
            <hr />
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 mt-10">
            <a href="<?= URL_ADMIN ?>/inc/productos/api/atributos/atributosAgregar.php?cod=<?= $cod ?>" data-title="Agregar Atributos" class="mb-5 btn btn-info modal-page-ajax">AGREGAR ATRIBUTOS +</a>
            <div id="listAttr">

            </div>
        </div>
        <div class="col-md-6 mt-10">
            <a href="<?= URL_ADMIN ?>/inc/productos/api/variaciones/variacionesAgregar.php?cod=<?= $cod ?>" data-title="Agregar Variaciones" class="mb-5 btn btn-info modal-page-ajax">AGREGAR VARIACIONES +</a>
            <div id="listComb" class="">

            </div>
        </div>
        <div class="clearfix"></div>
        <label class="col-md-12">
            <hr />
            Desarrollo:<br />
            <textarea name="desarrollo" class="ckeditorTextarea"><?= $producto["data"]["desarrollo"] ?></textarea>
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Palabras claves dividas por ,<br />
            <input type="text" name="keywords" value="<?= $producto["data"]["keywords"] ?>">
        </label>
        <label class="col-md-12">Descripción breve<br />
            <textarea name="description"><?= $producto["data"]["description"] ?></textarea>
        </label>
        <br />
        <div class="col-md-12">
            <div class="form-group form-check">

            </div>
        </div>
        <br />
        <div class="col-md-12">
            <div class="row">
                <?php
                if (!empty($producto['images'])) {
                    foreach ($producto['images'] as $img) {
                ?>
                        <div class='col-md-2 mb-20 mt-20'>
                            <div style="height:200px;background:url(<?= '../' . $img['ruta']; ?>) no-repeat center center/contain;">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="<?= URL_ADMIN . '/index.php?op=productos&accion=modificar&cod=' . $img['cod'] . '&borrarImg=' . $img['id'] ?>" class="btn btn-sm btn-block btn-danger">
                                        BORRAR IMAGEN
                                    </a>
                                </div>
                                <div class="col-md-5 text-right">
                                    <select onchange='$(location).attr("href", "<?= CANONICAL ?>&idImg=<?= $img["id"] ?>&ordenImg="+$(this).val())'>
                                        <?php
                                        for ($i = 0; $i <= count($producto['images']); $i++) {
                                            if ($img["orden"] == $i) {
                                                echo "<option value='$i' selected>$i</option>";
                                            } else {
                                                echo "<option value='$i'>$i</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <i>orden</i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <label class="col-md-7">Imágenes:<br />
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
        </label>
        <br />
        <div class="clearfix"><br /></div>
        <div class="col-md-12">
            <label><input type="checkbox" name="estado" value="1" <?php if ($producto["data"]["variable9"] == 1) {
                                                                        echo "checked";
                                                                    } ?>> Mostrar en la Web</label>
        </div>
        <div class="col-md-12">
            <label><input type="checkbox" name="envioGratis" value="1" <?php if ($producto["data"]["variable10"] == 1) {
                                                                            echo "checked";
                                                                        } ?>> Envio Gratis</label>
        </div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Producto" />
        </div>
</div>
<script>
    $("#pes").inputSpinner();

    setInterval(ML, 1000);

    function ML() {
        if ($('#meli').prop('checked') == false && $('#cod_meli').val() == '') {
            $('#cod_meli').attr('disabled', false);
            $('#meli').attr('disabled', false);
            $('#stock').attr('min', 0);
        }
    }

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

    checkAttrProducts();
    checkCombProducts();
</script>