<?php
$funcionesNav = new Clases\PublicFunction();
//Clases
$imagenesNav = new Clases\Imagenes();
$usuario = new Clases\Usuarios();
$categoriasNav = new Clases\Categorias();
$carrito = new Clases\Carrito();
//
if (isset($_POST["login"])):
    $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
    $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');

    $usuario->set("email", $email);
    $usuario->set("password", $password);

    if ($usuario->login() == 0):
        ?>
        <script>
            $(document).ready(function () {
                $('#errorLogin').html('<br/><div class="alert alert-warning" role="alert">Email o contraseña incorrecta.</div>');
                $('#login').modal("show");
            });
        </script>
    <?php
    else:
        $funcionesNav->headerMove(CANONICAL);
    endif;
endif;
?>
<script>
    $('#login').modal("show");
</script>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myLogin" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login_title">
                    <h2>Iniciar Sesión</h2>
                    <p>Si aún no estas registrado, haz click <a href="#registrar" onclick="$('.modal').modal('hide');"
                                                                data-toggle="modal">aquí</a>.</p>
                </div>
                <div id="errorLogin"></div>
                <form class="login_form row" id="login" method="post">
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="email" placeholder="Correo electrónico" name="email"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="password" placeholder="Contraseña" name="password"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <h4><a href="#">¿Olvidaste tu contraseña?</a></h4>
                    </div>
                    <div class="col-lg-12 form-group">
                        <button type="submit" name="login" class="btn update_btn form-control">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
<!-- REGISTRAR -->
<?php
if (isset($_POST["registrar"])):
    if ($_POST["password"] == $_POST["password2"]):
        $nombre = $funcionesNav->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
        $apellido = $funcionesNav->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
        $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
        $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
        $cod = substr(md5(uniqid(rand())), 0, 10);
        $fecha = getdate();
        $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

        $usuario->set("cod", $cod);
        $usuario->set("nombre", $nombre);
        $usuario->set("apellido", $apellido);
        $usuario->set("email", $email);
        $usuario->set("password", $password);
        $usuario->set("fecha", $fecha);

        if ($usuario->add() == 0):
            ?>
            <script>
                $(document).ready(function () {
                    $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">El email ya está registrado.</div>');
                    $('#registrar').modal("show");
                });
            </script>
        <?php
        else:
            $usuario->login();
            $funcionesNav->headerMove(CANONICAL);
        endif;
    else:
        ?>
        <script>
            $(document).ready(function () {
                $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">Las contraseñas no coinciden.</div>');
                $('#registrar').modal("show");
            });
        </script>
    <?php
    endif;
endif;
?>

<div class="modal fade" id="registrar" tabindex="-1" role="dialog" aria-labelledby="registrar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login_title">
                    <h2>Registro</h2>
                </div>
                <p id="errorRegistro"></p>
                <form class="login_form row" id="registro" method="post">
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="text" placeholder="Nombre" name="nombre"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="text" placeholder="Apellido" name="apellido"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="email" placeholder="Email" name="email"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="password" placeholder="Contraseña" name="password"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="password" placeholder="Confirmar Contraseña"
                               name="password2"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <button type="submit" name="registrar" class="btn subs_btn form-control">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Register modal -->
