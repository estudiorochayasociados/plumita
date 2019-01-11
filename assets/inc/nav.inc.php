<!--================Menu Area =================-->
<header class="shop_header_area carousel_menu_area">
    <div class="carousel_top_header row m0">
        <div class="container">
            <div class="carousel_top_h_inner">
                <div class="float-md-left">
                    <div class="top_header_left">
                        <div class="selector">
                            <select class="language_drop" name="countries" id="countries" style="width:300px;">
                                <option value='yt' data-image="<?= URL ?>/assets/img/icon/flag-1.png" data-imagecss="flag yt" data-title="English">English</option>
                                <option value='yu' data-image="<?= URL ?>/assets/img/icon/flag-1.png" data-imagecss="flag yu" data-title="Bangladesh">Bangla</option>
                                <option value='yt' data-image="<?= URL ?>/assets/img/icon/flag-1.png" data-imagecss="flag yt" data-title="English">English</option>
                                <option value='yu' data-image="<?= URL ?>/assets/img/icon/flag-1.png" data-imagecss="flag yu" data-title="Bangladesh">Bangla</option>
                            </select>
                        </div>
                        <select class="selectpicker usd_select">
                            <option>USD</option>
                            <option>$</option>
                            <option>$</option>
                        </select>
                    </div>
                </div>
                <div class="float-md-right">
                    <div class="top_header_middle">
                        <a href="#"><i class="fa fa-phone"></i>Telefono: <span><?=TELEFONO?></span></a>
                        <a href="#"><i class="fa fa-envelope"></i> Email: <span><?=EMAIL?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="carousel_menu_inner">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#"><img src="<?= URL ?>/assets/img/logo.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/index' ?>">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= URL . '/productos' ?>">Productos</a></li>

                        <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">lookbook</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    </ul>
                    <ul class="navbar-nav justify-content-end">
                        <li class="search_icon"><a href="#"><i class="icon-magnifier icons"></i></a></li>
                        <li class="user_icon"><a href="#"><i class="icon-user icons"></i></a></li>
                        <li class="cart_cart"><a href="#"><i class="icon-handbag icons"></i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>
<!--================End Menu Area =================-->