<?php

namespace Clases;

class TemplateSite
{
    public $title;
    public $keywords;
    public $description;
    public $imagen;
    public $favicon;
    public $body;

    private $config;
    private $contactData;

    public function __construct()
    {
        $this->body = '';
        $this->config = new Config();
        $this->contactData = $this->config->viewContact();
        $this->user = new Usuarios();
    }

    private $canonical = CANONICAL;
    private $autor = TITULO;
    private $copy = TITULO;

    private function head()
    {
        isset($_SESSION["usuarios"]["cod"]) ? $this->user->refreshSession($_SESSION["usuarios"]["cod"]) : '';
        echo '<!DOCTYPE html>';
        echo '<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">';
        echo '<head>';
        echo '<meta charset="utf-8"/>';
        echo '<meta name="author" lang="es" content="' . $this->autor . '" />';
        echo '<link rel="author" href="' . $this->contactData['data']['email'] . '" rel="nofollow" />';
        echo '<meta name="copyright" content="' . $this->copy . '" />';
        echo '<link rel="canonical" href="' . strip_tags($this->canonical) . '" />';
        echo '<meta name="distribution" content="global" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta name="rating" content="general" />';
        echo '<meta name="content-language" content="es-ar" />';
        echo '<meta name="DC.identifier" content="' . strip_tags($this->canonical) . '" />';
        echo '<meta name="DC.format" content="text/html" />';
        echo '<meta name="DC.coverage" content="' . $this->contactData['data']['pais'] . '" />';
        echo '<meta name="DC.language" content="es-ar" />';
        echo '<meta http-equiv="window-target" content="_top" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta http-equiv="content-language" content="es-ES" />';
        echo '<meta name="google" content="notranslate" />';
        echo '<meta name="geo.region" content="AR-X" />';
        echo '<meta name="geo.placename" content="' . $this->contactData['data']['provincia'] . '" />';
        echo '<meta name="geo.position" content="' . $this->contactData['data']['localidad'] . '" />';
        echo '<meta name="ICBM" content="' . $this->contactData['data']['localidad'] . '" />';
        echo '<meta content="public" name="Pragma" />';
        echo '<meta http-equiv="pragma" content="public" />';
        echo '<meta http-equiv="cache-control" content="public" />';
        echo '<meta property="og:url" content="' . strip_tags($this->canonical) . '" />';
        echo '<meta charset="utf-8">';
        echo '<meta content="IE=edge" http-equiv="X-UA-Compatible">';
        echo '<meta content="width=device-width, initial-scale=1" name="viewport">';
        echo '<meta name="language" content="Spanish">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />';
        echo '<title>' . strip_tags($this->title) . '</title>';
        echo '<meta http-equiv="title" content="' . strip_tags($this->title) . '" />';
        echo '<meta name="description" lang=es content="' . strip_tags($this->description) . '" />';
        echo '<meta name="keywords" lang=es content="' . strip_tags($this->keywords) . '" />';
        echo '<link href="' . URL . '/assets/img/favicon.png" rel="Shortcut Icon" />';
        echo '<meta name="DC.title" content="' . strip_tags($this->title) . '" />';
        echo '<meta name="DC.subject" content="' . strip_tags($this->description) . '" />';
        echo '<meta name="DC.description" content="' . strip_tags($this->description) . '" />';
        echo '<meta property="og:title" content="' . strip_tags($this->title) . '" />';
        echo '<meta property="og:description" content="' . strip_tags($this->description) . '" />';
        echo '<meta property="og:image" content="' . $this->imagen . '" />';
        echo '<link href="' . FAVICON . '" rel="Shortcut Icon" />';
        echo '<link href="' . URL . '/assets/css/font-awesome.min.css" rel="stylesheet">';
        echo '<link href="' .URL .'/vendors/line-icon/css/simple-line-icons.css" rel="stylesheet">';
        echo '<link href="' .URL .'/vendors/elegant-icon/style.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/bootstrap.min.css" rel="stylesheet">';

        echo '<link href="' .URL .'/vendors/revolution/css/settings.css" rel="stylesheet">';
        echo '<link href="' .URL .'/vendors/revolution/css/layers.css" rel="stylesheet">';
        echo '<link href="' .URL .'/vendors/revolution/css/navigation.css" rel="stylesheet">';

        echo '<link href="' .URL .'/vendors/bootstrap-selector/css/bootstrap-select.min.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/estilos-rocha.css" rel="stylesheet">';
        echo '<link href="' .URL .'/vendors/jquery-ui/jquery-ui.css" rel="stylesheet">';


        echo '<link href="' .URL .'/assets/css/progress-wizard.min.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/main-rocha.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/style.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/responsive.css" rel="stylesheet">';
        echo '<link href="' .URL .'/assets/css/estilos.css" rel="stylesheet">';
        echo '</head>';
        echo '<body class="common-home res layout-4">';
        echo '<div id="wrapper" class="wrapper-fluid banners-effect-5">';
    }

    private function foot()
    {
        require_once 'assets/inc/checkout/modals.php';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    }

    public function themeInit()
    {
        self::head();
        include 'assets/inc/nav.inc.php';
    }

    public function themeInitStages()
    {
        self::head();
        include 'assets/inc/checkout/nav.inc.php';
    }

    public function themeEnd()
    {
        include 'assets/inc/footer.inc.php';
        self::foot();
    }

    public function themeEndStages()
    {
        include 'assets/inc/checkout/footer.inc.php';
        self::foot();
    }

    public function set($atributo, $valor)
    {
        if (!empty($valor)) {
            $valor = $valor;
        } else {
            $valor = "NULL";
        }
        $this->$atributo = $valor;
    }
}
