<?php
$service = new Clases\ServicioTecnico();
include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
?>
<div class="col-md-12">
    <form method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="<?= URLSITE ?>/assets/archivos/ejemploServicioTecnico.xlsx" target="_blank">descargar modelo</a>)
        </h3>
        <hr/>
        <div class="row">
            <div class="col-md-12">
                <input type="file" name="excel" class="form-control" required/>
            </div>
            <br>
            <div class="col-md-12 mt-15">
                <input type="submit" name="submit" value="Verifica e importar archivo" class='btn  btn-info'/>
            </div>
        </div>
    </form>
    <?php

    if (isset($_POST['submit'])) {
        echo "<hr>";
        if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != "") {
            $allowedExtensions = array("xls", "xlsx");
            $objPHPExcel = PHPEXCEL_IOFactory::load($_FILES['excel']['tmp_name']);
            $objPHPExcel->setActiveSheetIndex(0);
            $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $numCols = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
            $numCols = (ord(strtolower($numCols)) - 96);

            $service->delete();

            $importar = '';
            if (!empty($numCols)) {
                for ($row = 2; $row <= $numRows; $row++) {
//                    $cod = substr(md5(uniqid(rand())), 0, 8);
                    $nombre = $objPHPExcel->getActiveSheet()->getCell("A" . $row)->getCalculatedValue();
                    if (empty($nombre)) continue;
                    $direccion = $funciones->antihack($objPHPExcel->getActiveSheet()->getCell("B" . $row)->getCalculatedValue());
                    $ciudad = $funciones->antihack($objPHPExcel->getActiveSheet()->getCell("C" . $row)->getCalculatedValue());
                    $provincia = $funciones->antihack($objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue());
                    $telefono = $funciones->antihack($objPHPExcel->getActiveSheet()->getCell("E" . $row)->getCalculatedValue());
                    $email = $funciones->antihack($objPHPExcel->getActiveSheet()->getCell("F" . $row)->getCalculatedValue());

                    //echo $nombre . "<br>";
                    //echo $provincia . "<br>";
                    //echo $direccion . "<br>";
                    //echo $ciudad . "<br>";
                    //echo $telefono . "<br>";
                    //echo $email . "<br><hr>";

                    $service->set("tecnico", $funciones->antihack_mysqli(isset($nombre) ? $nombre : ''));
                    $service->set("direccion", $funciones->antihack_mysqli(isset($direccion) ? $direccion : ''));
                    $service->set("ciudad", $funciones->antihack_mysqli(isset($ciudad) ? $ciudad : ''));
                    $service->set("provincia", $funciones->antihack_mysqli(isset($provincia) ? $provincia : ''));
                    $service->set("telefono", $funciones->antihack_mysqli(isset($telefono) ? $telefono : ''));
                    $service->set("email", $funciones->antihack_mysqli(isset($email) ? $email : ''));
                    $service->add();
                }
                echo '<span class="alert alert-success">Completado!</span>';
            } else {
                echo '<span class="alert alert-danger">Hay errores en el excel que intetas subir. Descargar aquí el ejemplo</span>';
            }
        } else {
            echo '<span class="alert alert-danger">Seleccionar primero el archivo a subir.</span>';
        }
    }
    ?>
</div>
