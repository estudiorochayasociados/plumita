<?php
$productos = new Clases\Productos();
$categoria = new Clases\Categorias();
$subcategoria = new Clases\Subcategorias();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include dirname(__DIR__, 3) . "/vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require dirname(__DIR__, 3) . "/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php";
?>
<div class="col-md-12">
    <form action="index.php?op=productos&accion=importar" method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="<?= URLADMIN ?>/index.php?op=productos&accion=exportar" target="_blank">descargar modelo</a>)
        </h3>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <input type="file" name="excel" class="form-control" required />
            </div>
            <div class="col-md-12">
                <?php
                if (isset($_POST['submit'])) {
                    if (isset($_FILES['excel']['name']) && $_FILES['excel']['name'] != "") {
                        $allowedExtensions = ["xls", "xlsx"];

                        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);

                        $sheet = $objPHPExcel->getActiveSheet()->toArray();

                        foreach ($sheet as $key => $cellVal) {
                            $ca = '';
                            $su = '';
                            if (empty($cellVal[1])) continue;
                            if ($key == 0) continue;

                            $categoria_row = trim(mb_strtoupper($cellVal[8]));
                            if (!empty($categoria_row)) {
                                $categoriaSearch = $categoria->list(["titulo='" . $categoria_row . "'", "area='productos'"], '', 1);
                                if (!empty($categoriaSearch)) {
                                    $ca = $categoriaSearch[0]['data']['cod'];
                                } else {
                                    $ca = substr(md5(uniqid(rand())), 0, 10);
                                    $categoria->set("cod", $ca);
                                    $categoria->set("titulo", $categoria_row);
                                    $categoria->set("descripcion", $categoria_row);
                                    $categoria->set("area", "productos");
                                    $categoria->add();
                                }

                                $subcategoria_row = trim(mb_strtoupper($cellVal[9]));
                                if (!empty($subcategoria_row)) {
                                    $subcategoriaSearch = $subcategoria->list(["titulo='" . $subcategoria_row . "'", "categoria='" . $ca . "'"], '', 1);
                                    if (!empty($subcategoriaSearch)) {
                                        $su = $subcategoriaSearch[0]['data']['cod'];
                                    } else {
                                        $su = substr(md5(uniqid(rand())), 0, 10);
                                        $subcategoria->set("cod", $su);
                                        $subcategoria->set("categoria", $ca);
                                        $subcategoria->set("titulo", $subcategoria_row);
                                        $subcategoria->add();
                                    }
                                }
                            }

                            $cod = substr(md5(uniqid(rand())), 0, 12);
                            $cod_producto = $funciones->antihack_mysqli($cellVal[0]);
                            $precio = $funciones->antihack_mysqli($cellVal[3]);
                            $precio = trim(str_replace("$", "", $precio));
                            $precio = str_replace(".", "", $precio);
                            $precio = str_replace(",", ".", $precio);
                            $precio_descuento = is_int($cellVal[4]) ? $funciones->antihack_mysqli($cellVal[4]) : 0;
                            $precio_mayorista = is_int($cellVal[5]) ? $funciones->antihack_mysqli($cellVal[5]) : 0;


                            $productos->set("cod", $cod);
                            $productos->set("cod_producto", $cod_producto);
                            $productos->set("titulo", $funciones->antihack_mysqli($cellVal[1]));
                            $productos->set("keywords", $funciones->antihack_mysqli($cellVal[1]));
                            $productos->set("desarrollo", $funciones->antihack_mysqli($cellVal[2]));
                            $productos->set("description", $funciones->antihack_mysqli($cellVal[2]));
                            $productos->set("precio", $precio);
                            $productos->precio_descuento = $precio_descuento;
                            $productos->precio_mayorista = $precio_mayorista;
                            $productos->set("stock", $funciones->antihack_mysqli($cellVal[6]));
                            $productos->set("peso", $funciones->antihack_mysqli($cellVal[7]));
                            $productos->set("categoria", $funciones->antihack_mysqli($ca));
                            $productos->set("subcategoria", $funciones->antihack_mysqli($su));
                            $productos->set("variable1", $funciones->antihack_mysqli($cellVal[10]));
                            $productos->set("variable2", $funciones->antihack_mysqli($cellVal[11]));
                            $productos->set("variable3", $funciones->antihack_mysqli($cellVal[12]));
                            $productos->set("fecha", date('Y-m-d'));

                            //CHEQUEAR SI EXISTE
                            $checkProduct = $productos->viewByCod($cod_producto);
                            if (empty($checkProduct['data'])) {
                                if ($productos->add()) {
                                    echo '<span class="alert btn-block alert-success  text-uppercase">' . $cellVal[1] . ' agregado</span><hr/>';
                                }
                            } else {
                                $cod =  $checkProduct['data']['cod'];
                                $productos->set("cod", $checkProduct['data']['cod']);
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

                            $pos = $key + 1;
                            $coor = "A" . $pos;

                            $array[] = $cellVal;
                        }
                    } else {
                        echo '<span class="alert alert-danger">Seleccionar primero el archivo a subir.</span>';
                    }
                }
                ?>
            </div>
            <div class="col-md-6">
                <input type="submit" name="submit" value="Verifica e importar archivo" class='btn  btn-info' />
            </div>
        </div>
    </form>
</div>