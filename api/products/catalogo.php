<?php
require_once "../../Config/Autoload.php";
Config\Autoload::run();
$funciones = new Clases\PublicFunction();
$productos = new Clases\Productos();
$limit = isset($_GET["limit"]) ? $_GET["limit"] : '';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($hola);
$mpdf->Output();


$data = $productos->list("", "categoria ASC", $limit);
foreach ($data as $product) {
    if ($product["data"]["titulo"]) {
?>
        <div style="width:200px; height:300px;margin:10px;float:left;">
            <img src="<?= URL . "/" . $product["images"][0]["ruta"] ?>" width="100%" />
            <span style="margin-top:10px;font-weight:bold;font-size:13px;display:block;text-transform:uppercase"><?= $product["data"]["titulo"] ?></span>
            <span style="font-size:12px;display:block"><?= $product["data"]["cod_producto"] ?></span>
            <span style="font-size:12px;display:block"><?= $product["category"]["data"]["titulo"] ?> - <?= $product["subcategory"]["data"]["titulo"] ?></span>
        </div>
<?php
    }
}
