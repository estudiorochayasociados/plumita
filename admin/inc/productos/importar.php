<?php
$productos = new Clases\Productos();
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();
$imagenes = new Clases\Imagenes();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
?>
<div class="col-md-12">
    <form action="index.php?op=productos&accion=importar" method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="<?= URLADMIN ?>/index.php?op=productos&accion=exportar" target="_blank">descargar modelo</a>)
        </h3>
        <hr/>
        <div class="row">

            <div class="col-md-12">
                <input type="file" name="excel" class="form-control" required/>
            </div>
            <div class="col-md-12 mt-10 mb-10">
                <!-- <label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="defaultUnchecked" name="vaciar" value="0" checked>
                        <label class="custom-control-label" for="defaultUnchecked">Vaciar base de datos e importar la base de datos</label>
                    </div>
                </label> -->
            </div>
            <div class="col-md-12">
                <?php
                if (isset($_POST['submit'])) {
                    if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != "") {
                        $allowedExtensions = array("xls", "xlsx");
                        $objPHPExcel = PHPEXCEL_IOFactory::load($_FILES['excel']['tmp_name']);
                        $objPHPExcel->setActiveSheetIndex(0);
                        $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
                        $numCols = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
                        $numCols = (ord(strtolower($numCols)) - 96);

                        // if (isset($_POST["vaciar"])) $productos->truncate();

                        if ($numCols == 15) {
                            for ($row = 2; $row <= $numRows; $row++) {
                                $ca = '';
                                $su = '';

                                $productos->set("stock", 50);
                                $productos->set("description", "");
                                $productos->set("keywords", "");
                                $productos->set("meli", "");
                                $productos->set("url", "");
                                $productos->set("peso", "1");
                                $productos->set("variable1", "");
                                $productos->set("variable2", "");
                                $productos->set("variable3", "");
                                $productos->set("variable4", "");
                                $productos->set("variable5", "");
                                $productos->set("variable6", "");
                                $productos->set("variable7", "");
                                $productos->set("variable8", "");
                                $productos->set("variable9", "");
                                $productos->set("variable10", "");

                                $titulo = $objPHPExcel->getActiveSheet()->getCell("A" . $row)->getCalculatedValue();
                                $precio_mayorista = $objPHPExcel->getActiveSheet()->getCell("D" . $row)->getCalculatedValue();
                                $precio = $objPHPExcel->getActiveSheet()->getCell("E" . $row)->getCalculatedValue();
                                $stock = $objPHPExcel->getActiveSheet()->getCell("F" . $row)->getCalculatedValue();
                                $peso = $objPHPExcel->getActiveSheet()->getCell("G" . $row)->getCalculatedValue();
                                $descripcion = $objPHPExcel->getActiveSheet()->getCell("H" . $row)->getCalculatedValue();
                                $variable1 = $objPHPExcel->getActiveSheet()->getCell("I" . $row)->getCalculatedValue();
                                $variable2 = $objPHPExcel->getActiveSheet()->getCell("J" . $row)->getCalculatedValue();
                                $variable3 = $objPHPExcel->getActiveSheet()->getCell("K" . $row)->getCalculatedValue();
                                $categoria_row = $objPHPExcel->getActiveSheet()->getCell("L" . $row)->getCalculatedValue();
                                $subcategoria_row = $objPHPExcel->getActiveSheet()->getCell("M" . $row)->getCalculatedValue();
                                $cod_producto = $objPHPExcel->getActiveSheet()->getCell("N" . $row)->getCalculatedValue();
                                $variable4 = $objPHPExcel->getActiveSheet()->getCell("O" . $row)->getCalculatedValue();

                                /*if (!empty($categoria_row)) {
                                    $categoria_row = mb_strtoupper($categoria_row);
                                    $categoriaSearch = $categoria->list(["titulo='" . $categoria_row . "'", "area='productos'"], '', 1);

                                    if (!empty($categoriaSearch)) {
                                        $ca = $categoriaSearch[0]['data']['cod'];
                                    } else {
                                        $ca = substr(md5(uniqid(rand())), 0, 6);
                                        $categoria->set("cod", $ca);
                                        $categoria->set("titulo", $categoria_row);
                                        $categoria->set("descripcion", $categoria_row);
                                        $categoria->set("area", "productos");
                                        $categoria->add();
                                    }

                                    if (!empty($subcategoria_row)) {
                                        $subcategoria_row = mb_strtoupper($subcategoria_row);
                                        $subcategoriaSearch = $subcategoria->list(["titulo='" . $subcategoria_row . "'", "categoria='" . $ca . "'"], '', 1);
                                        if (!empty($subcategoriaSearch)) {
                                            $su = $subcategoriaSearch[0]['data']['cod'];
                                        } else {
                                            $su = substr(md5(uniqid(rand())), 0, 6);
                                            $subcategoria->set("cod", $su);
                                            $subcategoria->set("categoria", $ca);
                                            $subcategoria->set("titulo", $subcategoria_row);
                                            $subcategoria->add();
                                        }
                                    }
                                }*/

                                $productos->set("titulo", $funciones->antihack_mysqli(isset($titulo) ? $titulo : ''));
                                $productos->set("keywords", $funciones->antihack_mysqli(isset($titulo) ? $titulo : ''));
                                $productos->set("desarrollo", $funciones->antihack_mysqli(isset($descripcion) ? $descripcion : ''));
                                $productos->set("description", $funciones->antihack_mysqli(isset($descripcion) ? $descripcion : ''));
                                $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($precio_descuento) ? $precio_descuento : ''));
                                $productos->set("precio_mayorista", $funciones->antihack_mysqli(isset($precio_mayorista) ? $precio_mayorista : ''));
                                $productos->set("precio", $funciones->antihack_mysqli(isset($precio) ? $precio : ''));
                                $productos->set("stock", $funciones->antihack_mysqli(isset($stock) ? $stock : ''));
                                $productos->set("peso", $funciones->antihack_mysqli(isset($peso) ? $peso : ''));
                                $productos->set("categoria", $funciones->antihack_mysqli(isset($ca) ? $ca : ''));
                                $productos->set("subcategoria", $funciones->antihack_mysqli(isset($su) ? $su : ''));
                                $productos->set("variable1", $funciones->antihack_mysqli(isset($variable1) ? $variable1 : ''));
                                $productos->set("variable2", $funciones->antihack_mysqli(isset($variable2) ? $variable2 : ''));
                                $productos->set("variable3", $funciones->antihack_mysqli(isset($variable3) ? $variable3 : ''));
                                $productos->set("cod_producto", $funciones->antihack_mysqli(isset($cod_producto) ? strval($cod_producto) : ''));
                                $productos->set("variable4", $funciones->antihack_mysqli(isset($variable4) ? $variable4 : ''));
                                $productos->set("fecha", date('Y-m-d'));
                                //CHEQUEAR SI EXISTE
                                $productos->set("variable7", "1");
                                $checkProduct = $productos->list(["cod_producto='".strval($cod_producto)."'", "variable7='1'"], '', '', false);

                                if (empty($checkProduct)) {
                                    $productos->set("cod", substr(md5(uniqid(rand())), 0, 4) . "_c" . substr(md5(uniqid(rand())), 0, 3));
                                    if ($productos->add()) {
                                        echo '<span class="alert btn-block alert-success  text-uppercase">' . $titulo . ' agregado</span><hr/>';
                                    }
                                } else {
                                    $productos->set("cod", $checkProduct[0]["data"]["cod"]);
                                    $productos->editSingle("titulo", $titulo);
                                    $productos->editSingle("precio", $precio);
                                    $productos->editSingle("precio_mayorista", $precio_mayorista);
                                    $productos->editSingle("stock", $stock);
                                    $productos->editSingle("categoria", $ca);
                                    $productos->editSingle("subcategoria", $su);
                                    $productos->editSingle("peso", $peso);
                                    $productos->editSingle("variable1", $variable1);
                                    $productos->editSingle("variable2", $variable2);
                                    $productos->editSingle("variable3", $variable3);
                                    $productos->editSingle("variable4", $variable4);
                                    echo '<span class="alert btn-block alert-warning  text-uppercase">' . $titulo . ' editado</span><hr/>';
                                }

                                //CHEQUEAR SI EXISTE
                                $productos->set("variable7", "2");
                                $checkProduct = $productos->list(["cod_producto='".strval($cod_producto)."'", "variable7='2'"], '', '1', false);

                                if (empty($checkProduct)) {
                                    $productos->set("cod", substr(md5(uniqid(rand())), 0, 4) . "_p" . substr(md5(uniqid(rand())), 0, 3));
                                    if ($productos->add()) {
                                        echo '<span class="alert btn-block alert-success  text-uppercase">' . $titulo . ' agregado</span><hr/>';
                                    }
                                } else {
                                    $productos->set("cod", strval($checkProduct[0]["data"]["cod"]));
                                    $productos->editSingle("titulo", $titulo);
                                    $productos->editSingle("precio", $precio);
                                    $productos->editSingle("precio_mayorista", $precio_mayorista);
                                    $productos->editSingle("stock", $stock);
                                    $productos->editSingle("categoria", $ca);
                                    $productos->editSingle("subcategoria", $su);
                                    $productos->editSingle("peso", $peso);
                                    $productos->editSingle("variable1", $variable1);
                                    $productos->editSingle("variable2", $variable2);
                                    $productos->editSingle("variable3", $variable3);
                                    $productos->editSingle("variable4", $variable4);
                                    echo '<span class="alert btn-block alert-warning  text-uppercase">' . $titulo . ' editado</span><hr/>';
                                }
                            }
                        } else {
                            echo '<span class="alert alert-danger">Hay errores en el excel que intetas subir. Descargar aquí el ejemplo</span>';
                        }
                    } else {
                        echo '<span class="alert alert-danger">Seleccionar primero el archivo a subir.</span>';
                    }
                }
                ?>
            </div>
            <div class="col-md-6">
                <input type="submit" name="submit" value="Verifica e importar archivo" class='btn  btn-info'/>
            </div>
        </div>
    </form>
</div>
