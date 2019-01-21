<?php

namespace Clases;

class TemplateSite
{

    public $title;
    public $keywords;
    public $description;
    public $favicon;
    public $canonical;
    public $autor;
    public $made;
    public $copy;
    public $pais;
    public $place;
    public $position;
    public $imagen;

    public function themeInit()
    {
        ?>
        <!DOCTYPE html>
        <html lang="es">
    <head>
        <!-- Icon css link -->
        <link href="<?=URL?>/assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?=URL?>/vendors/line-icon/css/simple-line-icons.css" rel="stylesheet">
        <link href="<?=URL?>/vendors/elegant-icon/style.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?=URL?>/assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Rev slider css -->
        <link href="<?=URL?>/vendors/revolution/css/settings.css" rel="stylesheet">
        <link href="<?=URL?>/vendors/revolution/css/layers.css" rel="stylesheet">
        <link href="<?=URL?>/vendors/revolution/css/navigation.css" rel="stylesheet">

        <!-- Extra plugin css -->
        <link href="<?=URL?>/vendors/bootstrap-selector/css/bootstrap-select.min.css" rel="stylesheet">

        <!--Productos-->
        <link href="<?=URL?>/vendors/jquery-ui/jquery-ui.css" rel="stylesheet">

        <link href="<?=URL?>/assets/css/style.css" rel="stylesheet">
        <link href="<?=URL?>/assets/css/responsive.css" rel="stylesheet">
        <link href="<?=URL?>/assets/css/estilos.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132855724-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-132855724-1');
        </script>
        <?php

        echo '<meta charset="utf-8"/>';
        echo '<meta name="author" lang="es" content="' . $this->autor . '" />';
        echo '<link rel="author" href="' . $this->made . '" rel="nofollow" />';
        echo '<meta name="copyright" content="' . $this->copy . '" />';
        echo '<link rel="canonical" href="' . $this->canonical . '" />';
        echo '<meta name="distribution" content="global" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta name="rating" content="general" />';
        echo '<meta name="content-language" content="es-ar" />';
        echo '<meta name="DC.identifier" content="' . $this->canonical . '" />';
        echo '<meta name="DC.format" content="text/html" />';
        echo '<meta name="DC.coverage" content="' . $this->pais . '" />';
        echo '<meta name="DC.language" content="es-ar" />';
        echo '<meta http-equiv="window-target" content="_top" />';
        echo '<meta name="robots" content="all" />';
        echo '<meta http-equiv="content-language" content="es-ES" />';
        echo '<meta name="google" content="notranslate" />';
        echo '<meta name="geo.region" content="AR-X" />';
        echo '<meta name="geo.placename" content="' . $this->place . '" />';
        echo '<meta name="geo.position" content="' . $this->position . '" />';
        echo '<meta name="ICBM" content="' . $this->position . '" />';
        echo '<meta content="public" name="Pragma" />';
        echo '<meta http-equiv="pragma" content="public" />';
        echo '<meta http-equiv="cache-control" content="public" />';
        echo '<meta property="og:url" content="' . $this->canonical . '" />';
        echo '<meta charset="utf-8">';
        echo '<meta content="IE=edge" http-equiv="X-UA-Compatible">';
        echo '<meta content="width=device-width, initial-scale=1" name="viewport">';
        echo '<meta name="language" content="Spanish">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />';
        echo '<title>' . $this->title . '</title>';
        echo '<meta http-equiv="title" content="' . $this->title . '" />';
        echo '<meta name="description" lang=es content="' . $this->description . '" />';
        echo '<meta name="keywords" lang=es content="' . $this->keywords . '" />';
        echo '<link href="' . $this->favicon . '" rel="Shortcut Icon" />';
        echo '<meta name="DC.title" content="' . $this->title . '" />';
        echo '<meta name="DC.subject" content="' . $this->description . '" />';
        echo '<meta name="DC.description" content="' . $this->description . '" />';
        echo '<meta property="og:title" content="' . $this->title . '" />';
        echo '<meta property="og:description" content="' . $this->description . '" />';
        echo '<meta property="og:image" content="' . $this->imagen . '" />';

        ?>
    </head>
        <body>
        <?php
    }

    public function themeNav()
    {
        include 'assets/inc/nav.inc.php';
    }

    public function themeSideIndex()
    {
        include 'assets/inc/sideIndex.inc.php';
    }

    public function themeSideBlog()
    {
        include 'assets/inc/sideBlog.inc.php';
    }

    public function themeEnd()
    {
        include 'assets/inc/footer.inc.php';
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }
}
