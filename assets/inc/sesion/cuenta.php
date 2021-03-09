<?php
//Clases
$hub = new Clases\Hubspot();
$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();
?>
<div class="col-md-12 mb-10">
    <?php
    if (isset($_POST["guardar"])) :

        $nombre = !empty($_POST["nombre"]) ? $funciones->antihack_mysqli($_POST["nombre"]) : '';
        $apellido = !empty($_POST["apellido"]) ? $funciones->antihack_mysqli($_POST["apellido"]) : '';
        $email = !empty($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '';
        $password = !empty($_POST["password"]) ? $funciones->antihack_mysqli($_POST["password"]) : '';
        $provincia = !empty($_POST["provincia"]) ? $funciones->antihack_mysqli($_POST["provincia"]) : '';
        $localidad = !empty($_POST["localidad"]) ? $funciones->antihack_mysqli($_POST["localidad"]) : '';
        $direccion = !empty($_POST["direccion"]) ? $funciones->antihack_mysqli($_POST["direccion"]) : '';
        $telefono = !empty($_POST["telefono"]) ? $funciones->antihack_mysqli($_POST["telefono"]) : '';
        $celular = !empty($_POST["celular"]) ? $funciones->antihack_mysqli($_POST["celular"]) : '';
        $postal = !empty($_POST["postal"]) ? $funciones->antihack_mysqli($_POST["postal"]) : '';

        if (!empty($_POST["password"]) && !empty($_POST["password2"])) :
            if ($_POST["password"] == $_POST['password2']) :
                $password = $funciones->antihack_mysqli($_POST["password"]);
            else :
                echo '<div class="alert alert-warning" role="alert">Las contraseña no coinciden</div>';
                $password = $usuarioData['data']['password'];
            endif;
        else :
            $password = $usuarioData['data']['password'];
        endif;

        $usuario->set("cod", $usuarioData['data']['cod']);

        $usuario->set("nombre", $nombre);
        $usuario->set("apellido", $apellido);
        $usuario->set("email", $email);
        $usuario->set("provincia", $provincia);
        $usuario->set("localidad", $localidad);
        $usuario->set("direccion", $direccion);
        $usuario->set("telefono", $telefono);
        $usuario->set("celular", $celular);
        $usuario->set("postal", $postal);
        $usuario->set("password", $password);
        $usuario->set("fecha", $usuarioData['data']['fecha']);
        $usuario->set("estado", 1);
        $usuario->set("invitado", 0);
        $usuario->set("minorista", 0);

        $hub->set("nombre", $nombre);
        $hub->set("apellido", $apellido);
        $hub->set("email", $email);
        $hub->set("direccion", $direccion);
        $hub->set("telefono", $telefono);
        $hub->set("celular", $celular);
        $hub->set("localidad", $localidad);
        $hub->set("provincia", $provincia);
        $hub->set("postal", $postal);
        if ($usuario->edit()) {
            echo "Contraseña actualizada";
        }
    endif;
    ?>
    <br>
    <form class="login_form" id="registro" method="post" autocomplete="off">
        <div class="row">
            <div class="col-md-6">Nombre
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['nombre'] ?>" type="text" placeholder="Nombre" name="nombre" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-user"></i></span>
                </div>
            </div>
            <div class="col-md-6">Apellido
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['apellido'] ?>" type="text" placeholder="Apellido" name="apellido" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-user"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">Email
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['email'] ?>" type="email" placeholder="Email" name="email" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-envelope"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">Teléfono
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['telefono'] ?>" type="number" placeholder="Teléfono" name="telefono" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-phone"></i></span>
                </div>
            </div>
            <div class="col-md-6">Celular
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['celular'] ?>" type="number" placeholder="Celular" name="celular" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-phone"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">Provincia
                <div class="input-group">
                    <select class="pull-right form-control h40" name="provincia" data-url="<?=URL?>" id="provincia" required>
                        <option value="<?= $usuarioData['data']['provincia'] ?>" selected><?= $usuarioData['data']['provincia'] ?></option>
                        <option value="" disabled>Provincia</option>
                        <?php $funciones->provincias() ?>
                    </select>
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
            <div class="col-md-6">Localidad
                <div class="input-group">
                    <select class="form-control h40" name="localidad" id="localidad" required>
                        <option value="<?= $usuarioData['data']['localidad'] ?>" selected><?= $usuarioData['data']['localidad'] ?></option>
                        <option value="" disabled>Localidad</option>
                    </select>
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">Dirección
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['direccion'] ?>" type="text" placeholder="Dirección" name="direccion" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
            <div class="col-md-6">Código Postal
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['data']['postal'] ?>" type="text" placeholder="Código Postal" name="postal" required />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
        </div>
        <br />
        <hr>
        <sup>*Dejar vacio si no se desea cambiar la contraseña</sup>
        <br>
        <div class="row">
            <div class="col-md-6">Contraseña
                <div class="input-group">
                    <input type="password" name="password" id="password_fake" class="hidden" autocomplete="off" style="display: none;">
                    <input autocomplete="off" class="form-control h40" value="" type="password" placeholder="Contraseña" name="password" />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-lock"></i></span>
                </div>
            </div>
            <div class="col-md-6">Confirmar Contraseña
                <div class="input-group">
                    <input class="form-control h40" value="" type="password" placeholder="Confirmar Contraseña" name="password2" />
                    <span class="input-group-addon"><i class="login_icon glyphicon glyphicon-lock"></i></span>
                </div>
            </div>
        </div>
        <br />
        <button style="width: 100%;" type="submit" name="guardar" class="btn btn-success">Guardar</button>
    </form>
    <br>
</div>