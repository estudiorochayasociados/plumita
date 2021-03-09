<div id="modalSP" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check-circle fs-90 " style="color:green"></i>
                    <br>
                    <div class="text-uppercase text-center">
                        <p class="fs-18 mt-10" style="margin:auto;width: 250px"> ¡FELICITACIONES! AGREGASTE UN NUEVO PRODUCTO A TU CARRITO</p>
                    </div>
                    <br/>
                    <a href="<?= URL . '/carrito' ?>" class="btn mt-20 fs-18 text-uppercase btn-secondary btn-block"><b>pasar por caja</b></a>

                    <a href="#productos" onclick="$('#modalSP').modal('toggle');" class="text-uppercase fs-12"><b>seguir
                            comprando</b></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalEP" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle fs-90 rojo" style="color:red"></i>
                    <br>
                    <span class="text-uppercase fs-16" id="error"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalS" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id="textS" class="text-center">
                </div>
            </div>
        </div>
    </div>
</div>
