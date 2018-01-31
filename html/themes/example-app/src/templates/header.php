<!DOCTYPE html>
<html>
    [:head]

    <body class="[:routeExtender] [:login]" data-hash="#top" data-plugin="goscroll">
        <header>
            <nav data-plugin="nav">
                <div class="logo col-xs-12 col-md-3" data-toggle data-toggle-class="nav-mobile-on">
                    <a href="/">
                        [:logo text left]
                        <img src="<?= Bc\App\Core\Util::getAsset(IMAGE_DIR, 'chicken-logo.png'); ?>"/>
                        [:logo text right]
                    </a>
                </div>
                <div class="menu col-xs-12 col-md-9" data-collapse>
                    [:nav]
                </div>
            </nav>
        </header>
        <div class="body [:has-sidebar body-class]">

            [:body]
