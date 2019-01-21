<?php
namespace config;
class autoload
{
    public static function runSitio()
    {
        require_once "Config/Minify.php";
        session_start();
        $_SESSION["usuarios"] = isset($_SESSION["usuarios"]) ? $_SESSION["usuarios"] : '';
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : substr(md5(uniqid(rand())), 0, 10);
        define('URL', "http://".$_SERVER['HTTP_HOST']."/plumita");
        define('CANONICAL', "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        define('GOOGLE_TAG', "");
        define('TITULO', "Plumita S.R.L");
        define('TELEFONO', "03564 422291");
        define('CIUDAD', "San Francisco");
        define('PROVINCIA', "Cordoba");
        define('EMAIL', "web@estudiorochayasoc.com.ar");//ventas@plumita.com
        define('EMAIL2', "davidmarcolini@plumita.com");
        define('PASS_EMAIL', "weAr2010");
        define('SMTP_EMAIL', "estudiorochayasoc.com.ar");
        define('DIRECCION', "Av. 9 de Septiembre 3203");
        define('LOGO', URL . "/assets/img/logo.jpg");
        define('FAVICON', URL . "/assets/img/favicon.png");
        define('APP_ID_FB', "");
        spl_autoload_register(
            function($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once $ruta;
            }
        );
    }

    public static function runSitio2()
    {
        spl_autoload_register(
            function($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../../".$ruta;
            }
        );
    }

    public static function runAdmin()
    {
        session_start();
        define('URLSITE',"http://".$_SERVER['HTTP_HOST']."/plumita");
        define('URL', "http://".$_SERVER['HTTP_HOST']."/plumita/admin");
        define('CANONICAL', "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        require_once "../Clases/Zebra_Image.php";
        spl_autoload_register(
            function ($clase)
            {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../" . $ruta;
            }
        );
    }
}
