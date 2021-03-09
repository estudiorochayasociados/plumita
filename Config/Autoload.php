<?php

namespace config;

class autoload
{
    //Ruta (https://www.rocha.com/'$project')
    //Ejemplos local: '/'.'Develop'
    //Ejemplos subido: ''
    private static $project = '/plumita';
    private static $title = 'Plumita';
    private static $titleAdmin = 'ESTUDIO ROCHA CMS';
    //http o https
    private static $protocol = 'https';
    private static $salt = 'salt@estudiorochayasoc.com.ar';
    private static $logo = '/assets/img/logo.jpg';
    private static $favicon = '/assets/img/favicon.png';

    //url para la exportacion de la imagen
    //('http://www.XXXXXXX.com.ar/assets/archivos/img_productos/'...)
    private static $urlImage = 'http://www.XXXXXXX.com.ar/assets/archivos/img_productos/';
    //Formato ('.jpg','.png')
    private static $urlImageFormat = '.jpg';

    public static function runSitio()
    {
        //        require_once "Config/Minify.php";
        session_start();
        $_SESSION["usuarios"] = isset($_SESSION["usuarios"]) ? $_SESSION["usuarios"] : '';
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : substr(md5(uniqid(rand())), 0, 10);
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/plumita");
        define('CANONICAL', "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        define('GOOGLE_TAG', "");
        define('TITULO', "Plumita S.R.L");
        define('TELEFONO', "03564 422291");
        define('CIUDAD', "San Francisco");
        define('PROVINCIA', "Cordoba");
        define('EMAIL', "ventas@plumita.com"); //ventas@plumita.com
        define('EMAIL2', "davidmarcolini@plumita.com");
        define('PASS_EMAIL', "weAr2010");
        define('SMTP_EMAIL', "cs1008.webhostbox.net");
        define('DIRECCION', "Av. 9 de Septiembre 3203");
        define('LOGO', URL . "/assets/img/logo.jpg");
        define('FAVICON', URL . "/assets/img/favicon.png");
        define('APP_ID_FB', "");
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once $ruta;
            }
        );
    }

    public static function runSitio2()
    {
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../../" . $ruta;
            }
        );
    }

    public static function runAdmin()
    {
        session_start();
        define('URLSITE', "https://" . $_SERVER['HTTP_HOST'] . "/plumita");
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/plumita/admin");
        define('CANONICAL', "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        require_once "../Clases/Zebra_Image.php";
        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                include_once "../" . $ruta;
            }
        );
    }

    public static function runCurl()
    {
        session_start();
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : strtoupper(substr(md5(uniqid(rand())), 0, 7));
        define('SALT', hash("sha256", self::$salt));
        define('URL', "https://" . $_SERVER['HTTP_HOST'] . "/plumita");
        define('LOGO', URL . "/assets/img/logo.jpg");
        define('TITULO', 's');
        spl_autoload_register(function ($clase) {
            $ruta = str_replace("\\", "/", $clase) . ".php";
            include_once "../../" . $ruta;
        });
    }
    public static function run()
    {
        self::settings();
        //require_once "Config/Minify.php";
        require  dirname(__DIR__) . '/vendor/autoload.php';
        define('SALT', hash("sha256", self::$salt));
        define('URL', self::$protocol . "://" . $_SERVER['HTTP_HOST'] . self::$project);
        define('TITULO_ADMIN', self::$titleAdmin);
        define('URL_ADMIN', self::$protocol . "://" . $_SERVER['HTTP_HOST'] . self::$project . "/admin");
        define('URLADMIN', self::$protocol . "://" . $_SERVER['HTTP_HOST'] . self::$project . "/admin");

        $_SESSION["images-folder"] = self::$protocol . "://" . $_SERVER['HTTP_HOST'] . self::$project . "";
        define('CANONICAL', self::$protocol . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        define('LOGO', URL . self::$logo);
        define('FAVICON', URL . "/assets/img/favicon.png");
        define('TITULO', self::$title);
        require_once dirname(__DIR__) . "/Clases/Zebra_Image.php";

        spl_autoload_register(
            function ($clase) {
                $ruta = str_replace("\\", "/", $clase) . ".php";
                $pos = strpos($ruta, "Clases");
                if ($pos !== false) {
                    include_once dirname(__DIR__) . "/" . $ruta;
                }
            }
        );
    }
    public static function settings()
    {
        
        #Se configura la zona horaria en Argentina
        setlocale(LC_ALL, 'es_RA.UTF-8');
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        #Se mantiene siempre la sesión iniciada
        session_start();
        #Se define el idioma de la pagina
        $_SESSION["lang"] = isset($_SESSION["lang"]) ? $_SESSION["lang"]  : "es" ;
        #Se genera el código de pedido
        $_SESSION["cod_pedido"] = isset($_SESSION["cod_pedido"]) ? $_SESSION["cod_pedido"] : strtoupper(substr(md5(uniqid(rand())), 0, 7));
        !isset($_SESSION['token']) ? $_SESSION['token'] = md5(uniqid(rand(), TRUE)) : null;
    }
}
