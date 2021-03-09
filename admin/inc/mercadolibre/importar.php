<?php
$config = new Clases\Config();
$meli = new Clases\MercadoLibre();
$productos = new Clases\Productos();
$meliConfig = $config->viewExportadorMeli();

?>
<div class="row">
    <div class="col-md-12 mb-20">
        <div class="text-center">
            <h1>Vincular productos a MercadoLibre
                <small style="font-size: 30%">v0.2.3</small>
            </h1>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-12 col-md-5">
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
                            Codigo Mercadolibre
                        </th>
                        <th>
                            Tipo de Publicación
                        </th>
                        <th>
                            Ajustes
                        </th>
                    </thead>
                    <tbody id="listMeli">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col col-xs-12 col-sm-12 col-md-7 mt-5">
        <form class="form-inline text-uppercase mt-10 mb-10" id="formMeli" method="POST" style="margin: auto;place-content: center" onsubmit="sync()">
            <input type="hidden" value="<?= $meliConfig["data"]["clasica"] ?>" id="cfg-classic" />
            <input type="hidden" value="<?= $meliConfig["data"]["premium"] ?>" id="cfg-premium" />
            <input type="hidden" value="<?= $meliConfig["data"]["calcular_envio"] ?>" id="cfg-premium" />

            <div>
                Tipo de Publicación
                <select id="type" class=" ml-5 ">
                    <option value="gold_special">Clásica</option>
                    <option value="gold_pro">Premium</option>
                </select>
            </div>
            <div class="ml-10">
                <button class="btn btn-success">SINCRONIZAR</button>
            </div>
        </form>
        <div class="mr-10" id="info">

        </div>

        <div class="row mt-10 mr-10" id="results">
            <table class='table text-center'>
                <thead class='thead-dark' style="border: 1px white;">
                    <tr>
                        <th class='text-center'>CÓDIGO</th>
                        <th class='text-center'>PRECIO</th>
                        <th class='text-center'>ESTADO</th>
                        <th class='text-center' style="width:200px">MENSAJE</th>
                    </tr>
                </thead>
                <tbody id='resultsRow'>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- MODALES -->
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
                                <input id="codProduct" name="codProduct" type="text" />
                            </b>
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div>
                            <b>Codigo de Mercadolibre:</b>
                            <input id="codMeli" name="codMeli" type="text" />
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <div>
                            <b>Tipo de publicación:</b><br>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="typeClassic" name="typeMeli" class="custom-control-input" value="gold_special">
                                <label class="custom-control-label" for="typeClassic">Clásica</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="typePremium" name="typeMeli" class="custom-control-input" value="gold_pro">
                                <label class="custom-control-label" for="typePremium">Premium</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addMeli()">Agregar</button>
            </div>
        </div>
    </div>
</div>

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
    $cod = isset($_GET["borrar"]) ? $funciones->antihack_mysqli($_GET["borrar"]) : '';
    $codProduct = isset($_GET["codProduct"]) ? $funciones->antihack_mysqli($_GET["codProduct"]) : '';
    $meli->set("code", $cod);
    $meli->remove();
    $check = $meli->checkProduct($codProduct);
    if (!$check) {
        $productos->set("cod", $codProduct);
        $productos->editSingle('meli', 0);
    } 
    $funciones->headerMove(URL_ADMIN . "/index.php?op=mercadolibre&accion=importar");
}
?>


<script>
    $(document).ready(function() {
        refreshlistMeli();
    });

    $(".porcentaje").inputSpinner();

    let classic = $('#cfg-classic').val();
    let premium = $('#cfg-premium').val();
    let shipping = $('#cfg-shipping').val();

    function delay() {
        return new Promise(resolve => setTimeout(resolve, 1000));
    }


    function addMeli() {
        event.preventDefault();
        let codProduct = $('#codProduct').val();
        let codMeli = $('#codMeli').val();
        let typeMeli = $('input:radio[name=typeMeli]:checked').val();

        $.ajax({
            url: "<?= URL_ADMIN ?>/api/ml/add-meli.php",
            type: "POST",
            data: {
                codProduct: codProduct,
                codMeli: codMeli,
                typeMeli: typeMeli
            },
            success: async function(data) {
                data = JSON.parse(data);
                if (data['status']) {
                    $('#listMeli').html('');
                    refreshlistMeli();
                    successMessage(data['message']);
                } else {
                    errorMessage(data['message']);
                }
            }
        });
    }


    function refreshlistMeli() {
        $.ajax({
            url: "<?= URL_ADMIN ?>/api/ml/refresh-list-meli.php",
            type: "POST",
            success: function(data) {
                data = JSON.parse(data);
                data.forEach(meliData => {
                    var meliPrint = `
                                <tr>
                                <td>` + meliData['data']['cod_producto'] + `</td>
                                <td>` + meliData['data']['code'] + `</td>
                                <td>` + meliData['data']['type'] + `</td>
                                <td class='text-center'>
                                <a class="btn btn-danger" style="margin-right: 0" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URLADMIN ?>/index.php?op=mercadolibre&accion=importar&borrar=` + meliData['data']['code'] + `&codProduct=` + meliData['data']['product'] + `">
                        <i class="fa fa-trash"></i></a>
                                </td>
                                </tr>
                            }
                        }
                        ?>`;
                    $('#listMeli').append(meliPrint);

                });
            }
        });
    }



    function sync() {
        event.preventDefault();
        $('#info').html('');
        $('#resultsRow').html('');
        $.ajax({
            url: "<?= URL_ADMIN ?>/api/ml/get-products.php",
            type: "POST",
            success: async function(data) {
                console.log(data);
                data = JSON.parse(data);
                if (data['status']) {
                    var total = data['products'].length;
                    $('#info').append("<h5 class='text-center'>Los productos se estan subiendo/actualizando en MercadoLibre, por favor aguarde y no cierre esta página.</h5>");
                    $('#info').append("<progress id='progress-bar' class='prb' max='" + total + "' value='0'></progress>");
                    $('#info').append("<input type='number' id='numTotal' value='0' /> / <input type='number' value='" + total + "' /><br>");
                    MeliTry(data['products']);
                }
            }
        });
    }

    async function MeliTry(products) {

        var a = 0;
        products.forEach(async (response) => {
            a++;
            await sendML(response, $('#type').val());
            console.log(a);
            if (a == 20) {
                await delay();
                a = 0
            }
        });
    }

    function sendML(product, type) {
        const form = $('#formMeli').serialize()
        return $.ajax({
            url: "<?= URL_ADMIN ?>/api/ml/to-meli.php",
            type: "POST",
            data: {
                product: product,
                type: type,
                form: {
                    'cfg-title': 0,
                    'cfg-price': 1,
                    'cfg-stock': 1,
                    'cfg-description': 0,
                    'cfg-images': 0
                }
            },
            success: function(data) {
                console.log(data)
                data = JSON.parse(data);
                data.forEach((response) => {
                    var error = '';
                    if (!response["status"]) {
                        response["error"].forEach((error_) => {
                            console.log(error_["message"]);
                            error = error_["message"];
                        });
                    }
                    $('#progress-bar').val($('#progress-bar').val() + 1);
                    $('#numTotal').val(Number($('#numTotal').val()) + 1);
                    classTr = (response['data']['status']) ? "bg-success" : "bg-danger";
                    statusIcon = (response['data']['status']) ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-remove'></i>";
                    $('#resultsRow').append("<tr class='" + classTr + " mr-10'><td>" + response["data"]["id"] + "</td><td>$" + response["data"]["price"] + "</td><td>" + statusIcon + "</td><td>" + error + "</td></tr>");
                });
            },
            error: function(e) {
                console.log(e);
            }
        });
    }
</script>