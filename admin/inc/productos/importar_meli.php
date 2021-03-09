<?php

$meli = new Clases\MercadoLibre();
$productos = new Clases\Productos();


$data = $meli->list('', '', '');
var_dump($data);
?>



<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Exportar productos a MercadoLibre
                    <small style="font-size: 30%">v0.2.3</small>
                </h1>
            </div>
            <div class="col-md-12 text-center">
                <!-- <button id="btnEx1" class="btn btn-success " onclick="exportMLType(1);" disabled>TODOS (EDITAR/AGREGAR)</button>
                <button id="btnEx2" class="btn btn-success " onclick="exportMLType(2);" disabled>SOLO PRODUCTOS NO VINCULADOS</button> -->
                <button id="btnEx3" class="btn btn-success " onclick="exportMLType(3);" disabled>ACTUALIZAR VINCULADOS</button>
                <button id="btnCfg" class="btn btn-primary" data-toggle="modal" data-target="#configModal"><i class="fa fa-cogs"></i></button>
            </div>
       
        <hr>
        <div class="col-md-6">
            <div class="mt-20">
                <div class="col-lg-12 col-md-12">
                    <h4>
                        Productos Vinculados
                        <button class="btn btn-success pull-right" data-toggle="modal" data-target="#modalAdd">
                            AGREGAR VINCULO
                        </button>
                    </h4>
                    <hr />
                    <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
                    <hr />
                    <table class="table  table-bordered  ">
                        <thead>
                            <th>
                                Codigo Producto
                            </th>
                            <th>
                                Producto
                            </th>
                            <th>
                                Codigo Mercadolibre
                            </th>
                            <th>
                                Ajustes
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($data)) {
                                foreach ($data as $data_) {
                                    echo "<tr>";
                                    echo "<td>" . strtoupper($data_['data']["titulo"]) . "</td>";
                                    echo "<td>" . strtoupper($data_['data']["titulo"]) . "</td>";
                                    echo "<td>" . strtoupper($data_['data']["peso"]) . " kg</td>";
                                    echo "<td>";
                                    echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URL_ADMIN . '/index.php?op=envios&accion=modificar&cod=' . $data_['data']["cod"] . '">
                        <i class="fa fa-cog"></i></a>';

                                    echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URL_ADMIN . '/index.php?op=envios&accion=ver&borrar=' . $data_['data']["cod"] . '">
                        <i class="fa fa-trash"></i></a>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-md-6 mt-20">
            <h4>
                Proceso de Vinculacion
            </h4>
            <div id="info">

            </div>
        </div>
        </div>

    </div>
    <div class="col-md-12 mt-5">
        <div class="row" id="results">
        </div>
    </div>
</div>
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Vinculo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <b>Codigo del Producto:
                        
                        <input  id="codProducto" name="codProducto"  type="text" />
                        </b>
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div>
                            <b>Codigo de Mercadolibre:</b>
                      
                        <input  id="codMeli" name="codMeli" type="text" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="enableExportBtn()">Agregar</button>
            </div>
        </div>
    </div>
</div>



<div id="modalS" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check-circle fs-90 " style="color:green"></i>
                    <br>
                    <div class="text-uppercase text-center">
                        <p class="fs-18 mt-10" style="margin:auto;width: 250px" id="textS"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalE" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle fs-90 " style="color:red"></i>
                    <br>
                    <span class="text-uppercase fs-16" id="error"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Configuraciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <label>
                            <b>% para publicacion Clasica</b>
                        </label>
                        <input data-suffix="%" class="porcentaje" id="cfg-classic" min="0" max="100" type="number" />
                    </div>
                    <div class="col-md-6 text-center">
                        <label>
                            <b>% para publicacion Premium</b>
                        </label>
                        <input data-suffix="%" class="porcentaje" id="cfg-premium" min="0" max="100" type="number" />
                    </div>
                </div>
                <hr>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label for="cfg-check-1" style="font-weight: 700;">
                            <input id="cfg-check-1" value="1" type="checkbox">
                            Calcular automaticamente el costo del envio por medio de MercadoLibre?
                        </label>
                        <p class="ml-15">
                            aca le explico que carajos es el checkbox este
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="enableExportBtn()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET["borrar"])) {
    $cod = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
    $envios->set("cod", $cod);
    $envios->delete();
    $funciones->headerMove(URL_ADMIN . "/index.php?op=envios");
}
?>
