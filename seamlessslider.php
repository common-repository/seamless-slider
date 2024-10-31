<?php
/*
Plugin Name: Seamless Slider
Plugin URI: None
Description: Seamless Slider - Smooth slider for your Wordpress
Author: Guramidev
Version: 0.4.3
*/
try {

    require __DIR__ . '/classes/Config.php';
    require __DIR__ . '/classes/Helper.php';
    require __DIR__ . '/classes/PDOQB.php';
    require __DIR__ . '/classes/Adapter.php';
    require __DIR__ . '/classes/DBUpdater.php';

    SeamlessSlider\Helper::setConfig( __FILE__ );

    SeamlessSlider\DBUpdater::getInstance()->prefixTables();

    if( SeamlessSlider\Helper::isAjaxRequest() ) {

        require __DIR__ . '/classes/API.php';

        require '' . SeamlessSlider\Config::$API['filepath'] . '';
    }
    else {

        if( SeamlessSlider\Adapter::is_admin() ) {

            require __DIR__ . '/classes/Admin.php';

            new SeamlessSlider\Admin( __FILE__ );

        }
        else {

            SeamlessSlider\DBUpdater::getInstance()->prepare()->handleUpdate();

            require __DIR__ . '/classes/Front.php';

            new SeamlessSlider\Front( __FILE__ );

        }

    }

}
catch( Exception $e ) {

    echo 'Faster Slider Plugin Error - ' . $e->getMessage();

}
