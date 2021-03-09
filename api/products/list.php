<?php
require_once dirname(__DIR__, 2) . "/Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();

if (empty($_POST["title"])) {
    unset($_POST["title"]);
}

$title = $funciones->antihack_mysqli(isset($_POST['title']) ? $_POST['title'] : '');
$order = $funciones->antihack_mysqli(isset($_GET['order']) ? $_GET['order'] : '');
$start = $funciones->antihack_mysqli(isset($_GET['start']) ? $_GET['start'] : '0');
$limit = $funciones->antihack_mysqli(isset($_GET['limit']) ? $_GET['limit'] : '24');


if (!empty($title)) {
    $filter[] = "(titulo LIKE '%$title%' OR keywords LIKE '%$title%'  OR variable2 LIKE '%$title%')";
}

//VARIABLE DE VEHICULOS
if (!empty($_POST['variable1'])) {
    $variable1Filter = '';
    foreach ($_POST['variable1'] as $key => $variable1) {
        $variable1 = $funciones->antihack_mysqli($variable1);
        $key == count($_POST['variable1']) - 1 ? $or = '' : $or = ' OR ';
        $variable1Filter .= !empty($variable1) ? "variable1='" . $variable1 . "'" . $or : '';
    }
    $filter[] = "(" . $variable1Filter . ")";
}
//FIN VARIABLE DE VEHICULOS

//VARIABLE DE MARCAS
if (!empty($_POST['variable2'])) {
    $variable2Filter = '';
    foreach ($_POST['variable2'] as $key => $variable2) {
        $variable2 = $funciones->antihack_mysqli($variable2);
        $key == count($_POST['variable2']) - 1 ? $or = '' : $or = ' OR ';
        $variable2Filter .= !empty($variable2) ? "variable2='" . $variable2 . "'" . $or : '';
    }
    $filter[] = "(" . $variable2Filter . ")";
}
//FIN VARIABLE DE MARCAS


//VARIABLE DE MODELO
if (!empty($_POST['variable3'])) {
    $variable3Filter = '';
    foreach ($_POST['variable3'] as $key => $variable3) {
        $variable3 = $funciones->antihack_mysqli($variable3);
        $key == count($_POST['variable3']) - 1 ? $or = '' : $or = ' OR ';
        $variable3Filter .= !empty($variable3) ? "variable3='" . $variable3 . "'" . $or : '';
    }
    $filter[] = "(" . $variable3Filter . ")";
}
// FIN VARIABLE DE MODELO
if (empty($title)) {

    if (!empty($_POST['categories'])) {
        $categoryFilter = '';
        foreach ($_POST['categories'] as $key => $cat) {
            $cat_ = $funciones->antihack_mysqli($cat);
            $key == count($_POST['categories']) - 1 ? $or = '' : $or = ' OR ';
            $categoryFilter .= !empty($cat_) ? "categoria='" . $cat_ . "'" . $or : '';
        }
        $filter[] = $categoryFilter;
    }

    if (!empty($_POST['subcategories'])) {
        $subcategoryFilter = '';
        foreach ($_POST['subcategories'] as $key => $cat) {
            $subcat_ = $funciones->antihack_mysqli($cat);

            $key == count($_POST['subcategories']) - 1 ? $or = '' : $or = ' OR ';
            //echo $key;
            $subcategoryFilter .= !empty($subcat_) ? "subcategoria='" . $subcat_ . "'" . $or : '';
            //$subcategoryFilter .= !empty($cat_) ? "variable1='" . $cat_ . "'" . $or : '';
        }


        $filter[] = (count($_POST['subcategories']) == 1) ? $subcategoryFilter : "(" . $subcategoryFilter . ")";
    }
}

switch ($order) {
    default:
        $order = "id DESC";
        break;
    case "2":
        $order = "precio ASC";
        break;
    case "3":
        $order = "precio DESC";
        break;
}
$filter[] = "variable9 = '1'";

$productosData = $producto->list($filter, $order, $start . "," . $limit);
if (!empty($productosData)) { ?>
    <?php foreach ($productosData as $producto) {
    ?>
        <div class="col-md-4 col-xs-6 mt-20">
            <div class="product-layout product-grid ">
                <div class="item-inner product-layout transition product-grid ">
                    <div class="container-fluid product-item-container">
                        <div class="left-block hidden-xs">
                            <div style="background: url('<?= URL . "/" . $producto["images"][0]['ruta'] ?>') no-repeat center center/contain;height:200px;width:100%"></div>
                        </div>
                        <div class="left-block hidden-md hidden-lg hidden-xl" style="height: unset;">
                            <div style="background: url('<?= URL . "/" . $producto["images"][0]['ruta'] ?>') no-repeat center center/contain;height:150px;width:100%"></div>
                        </div>
                        <div class="right-block" style="height: 150px;">
                            <h4 class="text-uppercase">
                                <a href="<?= $producto["link"] ?>" title="<?= $producto["data"]["titulo"] ?>" target="_self">
                                    <?= $producto["data"]["titulo"] ?>
                                </a>
                            </h4>
                            <div class="price mt-15">
                                <?php
                                if (!empty($producto['data']['precio'])) {
                                ?>
                                    <div class="single-product-price">
                                        <span class="price new-price fs-20"><?= !empty($producto['data']['precio_descuento']) ? "$" . $producto['data']['precio_descuento'] : "$" . $producto['data']['precio'] ?></span>
                                        <span class="regular-price tachado fs-14"><?= !empty($producto['data']['precio_descuento']) ? "$" . $producto['data']['precio'] : '' ?></span>
                                    </div>
                                <?php
                                } ?>
                            </div>
                            <div class="text-left">
                                <a href="<?= $producto["link"] ?>" class="btn btn-cart btn-cartVer mt-10 fs-13">
                                    Ver +
                                </a>
                                <?php if (!empty($producto['data']['stock']) && !empty($producto['data']['precio'])) { ?>
                                    <a class="pull-right btn btn-cart btn-cartAgregar  mt-10 fs-13" onclick="addToCartQuick('<?= $producto['data']['cod'] ?>',1,'<?= URL ?>')">+</a>
                                <?php } else { ?>
                                    <div class="pull-right btn btn-cart btn-cartSinStock  mt-10 fs-13" style="background-color: #CC0033;">STOCK</div>
                                <?php  } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } ?>
<?php }
