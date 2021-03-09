<?php
$descuento = new Clases\Descuentos();
$productos = new Clases\Productos();
$categorias = new Clases\Categorias();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$descuento->set("cod", $cod);
$data = $descuento->view();

//productos descuento
$productosDescuentoCod = explode(',', $data['data']['productos_cod']);
$productosSavedArrayJson = json_encode($productos->listForManyCods($productosDescuentoCod));

//categorias descuento
$categoriasDescuentodCod = explode(',', $data['data']['categorias_cod']);
$categoriasSavedArrayJson = json_encode($categorias->listForManyCods($categoriasDescuentodCod));

//subcategorias descuento
$subcategoriasDescuentoCod = explode(',', $data['data']['subcategorias_cod']);
$subcategoriasSavedArrayJson = json_encode($categorias->listSubcategoriesForManyCods($subcategoriasDescuentoCod));

//lista de categorias y subcategorias
$categoriasArray = $categorias->list(["area = 'productos'"], "", "");
$subcategoriasArray = [];

foreach ($categoriasArray as $categoria) {
    foreach ($categoria["subcategories"] as $subcategoria) {
        $subcategoria["categoriaTitulo"] = $categoria['data']['titulo'];
        $subcategoriasArray[] = $subcategoria;
    }
}

$categoriasArrayJson = json_encode(["status" => true, "category" => $categoriasArray]);
$subcategoriasArrayJson = json_encode(["status" => true, "subcategory" => $subcategoriasArray]);


//agregar
if (isset($_POST["modificar"])) {
    $descuento->set("id", $data['data']['id']);
    $descuento->set("cod", $funciones->antihack_mysqli(isset($_POST["cod"]) ? $_POST["cod"] : ''));
    $descuento->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $descuento->set("tipo", $funciones->antihack_mysqli(isset($_POST["tipo"]) ? $_POST["tipo"] : ''));
    $descuento->set("monto", $funciones->antihack_mysqli(isset($_POST["monto"]) ? $_POST["monto"] : ''));
    $descuento->set("productos_cod", $funciones->antihack_mysqli(isset($_POST["productTags"]) ? $_POST["productTags"] : ''));
    $descuento->set("categorias_cod", $funciones->antihack_mysqli(isset($_POST["categoryTags"]) ? $_POST["categoryTags"] : ''));
    $descuento->set("subcategorias_cod", $funciones->antihack_mysqli(isset($_POST["subcategoryTags"]) ? $_POST["subcategoryTags"] : ''));
    $descuento->set("sector", $funciones->antihack_mysqli(isset($_POST["sector"]) ? $_POST["sector"] : ''));
    $descuento->set("fecha_inicio", $funciones->antihack_mysqli(isset($_POST["fecha-inicio"]) ? $_POST["fecha-inicio"] : ''));
    $descuento->set("fecha_fin", $funciones->antihack_mysqli(isset($_POST["fecha-fin"]) ? $_POST["fecha-fin"] : ''));
    $descuento->set("todosProductos", $funciones->antihack_mysqli(isset($_POST["todos-productos"]) ? $_POST["todos-productos"] : 0));
    $descuento->set("todasCategorias", $funciones->antihack_mysqli(isset($_POST["todas-categorias"]) ? $_POST["todas-categorias"] : 0));
    $descuento->set("todasSubcategorias", $funciones->antihack_mysqli(isset($_POST["todas-subcategorias"]) ? $_POST["todas-subcategorias"] : 0));

    $descuento->edit();
    $funciones->headerMove(URLADMIN . "/index.php?op=descuentos");
}
?>

<div class="col-md-12">
    <h4>Descuentos</h4>
    <hr/>
    <form method="post" id="form-descuento" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Titulo:<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><b>T</b></span>
                </div>
                <input type="text" name="titulo" value="<?= $data["data"]["titulo"] ?>" required>
            </div>
        </label>
        <label class="col-md-4">Código descuento:<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-star"></i></span>
                </div>
                <input type="text" name="cod" value="<?= $data["data"]["cod"] ?>" required>
            </div>
        </label>
        <label class="col-md-2">Tipo:<br/>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect"><b>$ / %</b></label>
                </div>
                <select name="tipo" class="custom-select" id="inputGroupSelect">
                    <option selected disabled>Seleccionar...</option>
                    <option value="0" <?php if ($data["data"]["tipo"] == 0) {
                        echo 'selected';
                    } ?>>Efectivo
                    </option>
                    <option value="1" <?php if ($data["data"]["tipo"] == 1) {
                        echo 'selected';
                    } ?>>Porcentaje
                    </option>
                </select>
            </div>
        </label>
        <label class="col-md-2">Monto:<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><b>M</b></span>
                </div>
                <input type="number" name="monto" value="<?= $data["data"]["monto"] ?>" required>
            </div>
        </label>
        <label class="col-md-4">Aplica a:<br/>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect"><i class="fas fa-users"></i></label>
                </div>
                <select name="sector" class="custom-select" id="inputGroupSelect">
                    <option selected disabled>Seleccionar...</option>
                    <option value="0" <?php if ($data["data"]["sector"] == 0) {
                        echo 'selected';
                    } ?>>Todos los usuarios
                    </option>
                    <option value="1" <?php if ($data["data"]["sector"] == 1) {
                        echo 'selected';
                    } ?>>Solo a usuarios que no posean descuento
                    </option>
                    <option value="2" <?php if ($data["data"]["sector"] == 2) {
                        echo 'selected';
                    } ?>>Solo a usuarios que ya posean descuento
                    </option>
                </select>
            </div>
        </label>
        <label class="col-md-4">Desde:<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" name="fecha-inicio" value="<?= $data["data"]["fecha_inicio"] ?>">
            </div>
        </label>
        <label class="col-md-4">Hasta:<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" name="fecha-fin" value="<?= $data["data"]["fecha_fin"] ?>">
            </div>
        </label>
        <label class="col-md-3">Buscar productos:
        (<input type="checkbox" value="1" name="todos-productos" <?= ($data["data"]["todos_productos"] == 1) ? 'checked' : ''?>> Todos los productos)<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" list="productList" id="product">
            </div>
            <datalist id="productList"></datalist>
        </label>
        <label class="col-md-12">
            <input data-beautify="false" name="productTags" type="text" class="productTags">
        </label>
        <label class="col-md-3">Buscar categorias:
            (<input type="checkbox" value="1" name="todas-categorias" <?= ($data["data"]["todas_categorias"] == 1) ? 'checked' : ''?>> Todas las categorias)<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" list="categoryList" id="category">
            </div>
            <datalist id="categoryList"></datalist>
        </label>
        <label class="col-md-12">
            <input data-beautify="false" name="categoryTags" type="text" class="categoryTags">
        </label>
        <label class="col-md-3">Buscar subcategorias:
            (<input type="checkbox" value="1" name="todas-subcategorias" <?= ($data["data"]["todas_subcategorias"] == 1) ? 'checked' : ''?>> Todas las subcategorias)<br/>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" list="subcategoryList" id="subcategory">
            </div>
            <datalist id="subcategoryList"></datalist>
        </label>
        <label class="col-md-12">
            <input data-beautify="false" name="subcategoryTags" type="text" class="subcategoryTags">
        </label>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" id="modificar" name="modificar" value="Modificar Descuento"/>
        </div>
    </form>
</div>


<script>
    $(function () {

        $('.productTags').tokenfield();
        $('.categoryTags').tokenfield();
        $('.subcategoryTags').tokenfield();

        var productTags = [];
        var categoryTags = [];
        var subcategoryTags = [];

        function completeFields() {
            dataProducts = JSON.parse('<?=$productosSavedArrayJson?>');
                $.each(dataProducts, function (index, value) {
                    productTags = $('.productTags').tokenfield('getTokens');
                    productTags.push({
                        value: value.data.cod,
                        label: value.data.titulo+' | '+value.data.cod_producto
                    });
                    $('.productTags').tokenfield('setTokens', productTags);
                });
            dataCategories = JSON.parse('<?=$categoriasSavedArrayJson?>');
            $.each(dataCategories, function (index, value) {
                categoryTags = $('.categoryTags').tokenfield('getTokens');
                categoryTags.push({
                    value: value.data.cod,
                    label: value.data.titulo
                });
                $('.categoryTags').tokenfield('setTokens', categoryTags);
            });
            dataSubcategories = JSON.parse('<?=$subcategoriasSavedArrayJson?>');
            $.each(dataSubcategories, function (index, value) {
                subcategoryTags = $('.subcategoryTags').tokenfield('getTokens');
                subcategoryTags.push({
                    value: value.data.cod,
                    label: value.categoriaTitulo + ' | ' + value.data.titulo
                });
                $('.subcategoryTags').tokenfield('setTokens', subcategoryTags);
            });
        }
        completeFields();

        $("#product").keyup(function () {
            if ($(this).val().length == 3) {
                $("#productList").empty();
                refreshProductData($(this).val());
            }

            $('#productList option').each(function () {
                if ($(this).val() == $("#product").val()) {
                    $("#product").val('');
                    productTags = $('.productTags').tokenfield('getTokens');
                    productTags.push({
                        value: $(this).val(),
                        label: $(this).attr('label')
                    });
                    $('.productTags').tokenfield('setTokens', productTags);
                }
            });

        });

        function refreshProductData(currentValue) {
            let url = '<?=URL?>';
            $.ajax({
                url: url + "/api/descuentos/productos.php",
                type: "POST",
                data: {string: currentValue},
                success: function (data) {
                    data = JSON.parse(data);

                    if (data['status'] == true) {
                        $.each(data.productos, function (index, value) {
                            $("#productList").append('<option value="' + value.data.cod + '" label="' + value.data.titulo + ' | ' + value.data.cod_producto +'">');
                        });

                    } else {
                        console.log('error');
                    }
                }
            });
        }

        $("#category").keyup(function () {
            if ($(this).val().length == 3) {
                $("#categoryList").empty();
                refreshCategoryData();
            }

            $('#categoryList option').each(function () {
                if ($(this).val() == $("#category").val()) {
                    $("#category").val('');
                    categoryTags = $('.categoryTags').tokenfield('getTokens');
                    categoryTags.push({value: $(this).val(), label: $(this).attr('label')});
                    $('.categoryTags').tokenfield('setTokens', categoryTags);
                    console.log(categoryTags);
                }
            });

        });

        function refreshCategoryData() {
            data = JSON.parse('<?=$categoriasArrayJson?>');

            if (data['status'] == true) {
                $.each(data.category, function (index, value) {
                    $("#categoryList").append('<option value="' + value.data.cod + '" label="' + value.data.titulo + '">');
                });

            } else {
                console.log('error');
            }
        }

        $("#subcategory").keyup(function () {
            if ($(this).val().length == 3) {
                $("#subcategoryList").empty();
                refreshSubcategoryData();
            }

            $('#subcategoryList option').each(function () {
                if ($(this).val() == $("#subcategory").val()) {
                    $("#subcategory").val('');
                    subcategoryTags = $('.subcategoryTags').tokenfield('getTokens');
                    subcategoryTags.push({value: $(this).val(), label: $(this).attr('label')});
                    $('.subcategoryTags').tokenfield('setTokens', subcategoryTags);
                    console.log(subcategoryTags);
                }
            });

        });

        function refreshSubcategoryData() {
            data = JSON.parse('<?=$subcategoriasArrayJson?>');

            if (data['status'] == true) {
                $.each(data.subcategory, function (index, value) {
                    $("#subcategoryList").append('<option value="' + value.data.cod + '" label="' + value.categoriaTitulo + ' | ' + value.data.titulo + '">');
                });

            } else {
                console.log('error');
            }
        }

    });
</script>