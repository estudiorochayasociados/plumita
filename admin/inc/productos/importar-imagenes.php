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
    <form action="index.php?op=productos&accion=importar-imagenes" method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="<?= URLADMIN ?>/ejemplos/ejemplo-imagenes.xlsx" target="_blank">descargar modelo</a>)</h3>
        <hr/>
        <div class="row">

            <div class="col-md-12">
                <input type="file" name="excel" class="form-control" required/>
            </div>
            <div class="col-md-12 mt-10 mb-10">
                <label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="defaultUnchecked" name="vaciar" value="0" checked>
                        <label class="custom-control-label" for="defaultUnchecked">Vaciar base de datos e importar la base de datos</label>
                    </div>
                </label>
            </div>
            <div class="col-md-12">

                <?php
                $i = 0;
                if (isset($_POST['submit'])) {

                    if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != "") {
                        $allowedExtensions = array("xls", "xlsx");

                        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);

                        $sheet = $objPHPExcel->getActiveSheet()->toArray();
                        $array = [];

                        if (isset($_POST["vaciar"])) $productos->truncate();

                        foreach ($sheet as $cellVal) {
                            $categoria_row = mb_strtoupper($cellVal[8]);
                            $ca = '';
                            $su = '';


                            if (!empty($categoria_row)) {
                                $categoriaSearch = $categoria->list(["titulo='" . $categoria_row . "'", "area='productos'"], '', 1);

                                if (!empty($categoriaSearch)) {
                                    $ca = $categoriaSearch[0]['data']['cod'];
                                } else {
                                    $ca = substr(md5(uniqid(rand())), 0, 5);
                                    $categoria->set("cod", $ca);
                                    $categoria->set("titulo", $categoria_row);
                                    $categoria->set("descripcion", $categoria_row);
                                    $categoria->set("area", "productos");
                                    $categoria->add();
                                }

                                $subcategoria_row = mb_strtoupper($cellVal[9]);
                                if (!empty($subcategoria_row)) {
                                    $subcategoriaSearch = $subcategoria->list(["titulo='" . $subcategoria_row . "'", "categoria='" . $ca . "'"], '', 1);
                                    if (!empty($subcategoriaSearch)) {
                                        $su = $subcategoriaSearch[0]['data']['cod'];
                                    } else {
                                        $su = substr(md5(uniqid(rand())), 0, 5);
                                        $subcategoria->set("cod", $su);
                                        $subcategoria->set("categoria", $ca);
                                        $subcategoria->set("titulo", $subcategoria_row);
                                        $subcategoria->add();
                                    }
                                }
                            }

                            $productos->set("titulo", $funciones->antihack_mysqli($cellVal[1]));
                            $productos->set("keywords", $funciones->antihack_mysqli($cellVal[1]));
                            $productos->set("desarrollo", $funciones->antihack_mysqli($cellVal[2]));
                            $productos->set("description", $funciones->antihack_mysqli($cellVal[2]));
                            $productos->set("precio", $funciones->antihack_mysqli($cellVal[3]));
                            $productos->set("precio_descuento", $funciones->antihack_mysqli($cellVal[4]));
                            $productos->set("precio_mayorista", $funciones->antihack_mysqli($cellVal[5]));
                            $productos->set("stock", $funciones->antihack_mysqli($cellVal[6]));
                            $productos->set("peso", $funciones->antihack_mysqli($cellVal[7]));
                            $productos->set("categoria", $funciones->antihack_mysqli($ca));
                            $productos->set("subcategoria", $funciones->antihack_mysqli($su));
                            $productos->set("variable1", $funciones->antihack_mysqli($cellVal[10]));
                            $productos->set("variable2", $funciones->antihack_mysqli($cellVal[11]));
                            $productos->set("variable3", $funciones->antihack_mysqli($cellVal[12]));
                            $productos->set("cod_producto", $funciones->antihack_mysqli($cellVal[13]));
                            $productos->set("fecha", date('Y-m-d'));

                            //CHEQUEAR SI EXISTE
                            $productos->set("variable7", "1");
                            $checkProduct = $productos->list(["cod_producto='$cellVal[13]'", "variable7='1'"], '', '', false);

                            if (empty($checkProduct)) {
                                $cod = substr(md5(uniqid(rand())), 0, 4) . "_c" . substr(md5(uniqid(rand())), 0, 3);
                                $productos->set("cod", $cod);
                                if ($productos->add()) {
                                    echo '<span class="alert btn-block alert-success  text-uppercase">' . $cellVal[1] . ' agregado</span><hr/>';
                                }
                            } else {
                                $cod = $checkProduct[0]["data"]["cod"];
                                $productos->set("cod", $cod);
                                $productos->editSingle("titulo", $productos->get("titulo"));
                                $productos->editSingle("precio", $productos->get("precio"));
                                $productos->editSingle("precio_mayorista", $productos->get("precio_mayorista"));
                                $productos->editSingle("stock", $productos->get("stock"));
                                $productos->editSingle("categoria", $ca);
                                $productos->editSingle("subcategoria", $su);
                                $productos->editSingle("peso", $productos->get("peso"));
                                $productos->editSingle("variable1", $productos->get("variable1"));
                                $productos->editSingle("variable2", $productos->get("variable2"));
                                $productos->editSingle("variable3", $productos->get("variable3"));
                                $productos->editSingle("variable4", $productos->get("variable4"));

                                echo '<span class="alert btn-block alert-warning  text-uppercase">' . $cellVal[1] . ' editado</span><hr/>';
                            }

                            if (@$objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]) {
                                $nombre_imagen = substr(md5(uniqid(rand())), 0, 5);

                                $image = @$objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]->getPath();
                                $zipReader = fopen($image, 'r');
                                $imageContents = '';
                                while (!feof($zipReader)) {
                                    $imageContents .= fread($zipReader, 1024);
                                }

                                fclose($zipReader);
                                $extension = $objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]->getExtension();
                                $myFileName = "../archivos/img_productos/" . $nombre_imagen . '.' . $extension;

                                file_put_contents($myFileName, $imageContents);

                                $imagenes->set("cod", $cod);
                                $imagenes->set("ruta", str_replace("../", "", $myFileName));
                                $imagenes->add();

                                $cellVal[0] = $myFileName;
                                $imageContents = '';
                            }

                            //CHEQUEAR SI EXISTE
                            $productos->set("variable7", "2");
                            $checkProduct = $productos->list(["cod_producto='$cellVal[13]'", "variable7='2'"], '', '', false);

                            if (empty($checkProduct)) {
                                $cod = substr(md5(uniqid(rand())), 0, 4) . "_p" . substr(md5(uniqid(rand())), 0, 3);
                                $productos->set("cod", $cod);
                                if ($productos->add()) {
                                    echo '<span class="alert btn-block alert-success  text-uppercase">' . $cellVal[1] . ' agregado</span><hr/>';
                                }
                            } else {
                                $cod = $checkProduct[0]["data"]["cod"];
                                $productos->set("cod", $cod);
                                $productos->editSingle("titulo", $productos->get("titulo"));
                                $productos->editSingle("precio", $productos->get("precio"));
                                $productos->editSingle("precio_mayorista", $productos->get("precio_mayorista"));
                                $productos->editSingle("stock", $productos->get("stock"));
                                $productos->editSingle("categoria", $ca);
                                $productos->editSingle("subcategoria", $su);
                                $productos->editSingle("peso", $productos->get("peso"));
                                $productos->editSingle("variable1", $productos->get("variable1"));
                                $productos->editSingle("variable2", $productos->get("variable2"));
                                $productos->editSingle("variable3", $productos->get("variable3"));
                                $productos->editSingle("variable4", $productos->get("variable4"));

                                echo '<span class="alert btn-block alert-warning  text-uppercase">' . $cellVal[1] . ' editado</span><hr/>';
                            }

                            if (@$objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]) {
                                $nombre_imagen = substr(md5(uniqid(rand())), 0, 5);

                                $image = @$objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]->getPath();
                                $zipReader = fopen($image, 'r');
                                $imageContents = '';
                                while (!feof($zipReader)) {
                                    $imageContents .= fread($zipReader, 1024);
                                }

                                fclose($zipReader);
                                $extension = $objPHPExcel->getActiveSheet()->getDrawingCollection()[$i]->getExtension();
                                $myFileName = "../archivos/img_productos/" . $nombre_imagen . '.' . $extension;

                                file_put_contents($myFileName, $imageContents);

                                $imagenes->set("cod", $cod);
                                $imagenes->set("ruta", str_replace("../", "", $myFileName));
                                $imagenes->add();

                                $cellVal[0] = $myFileName;
                                $imageContents = '';
                            }
                            $array[] = $cellVal;
                            $i++;
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
