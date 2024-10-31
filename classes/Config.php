<?php

namespace SeamlessSlider;

class Config {

    static $TABLE_PREFIX = null; // set on run-time

    static $BLOG_ID = 1; // if different adjusted on run-time

    static $IS_MULTISITE = false; // if true adjusted on run-time

    static $DEVELOPMENT_MODE = 'production';

    static $CACHE = false;

    static $API = array(
        'dir_name' => 'api',
        'filename' => 'index.php',
    );

    static $PLUGIN = array(
        'icon_filename' => 'icon.png',
        'images_dir_name' => 'images',
        'fonts_dir_name' => 'fonts'
    );

    static $AJAX = array(
        'filename' => 'admin-ajax.php',
        'validator' => array(
            'key' => 'guramidev',
            'value' => 'Asefbnko1'
        )
    );
    static $ADMIN = array(
        'dir_name' => 'admin',
        'filename' => 'index.php',
        'js_dir_name' => 'scripts',
        'css_dir_name' => 'css',
        'templates_dir_name' => 'templates',
        'dev_styles' => array(
            array( 'handle' => 'index', 'src' => 'index.css' ),
            array( 'handle' => 'color-picker', 'src' => 'color-picker.css' ),
            array( 'handle' => 'media-streamer', 'src' => 'media-streamer.css' ),

            array( 'front' => true, 'handle' => 'faster-slider', 'src' => 'faster-slider.css' )
        ),
        'production_styles' => array(
            array( 'handle' => 'index', 'src' => 'index.css' ),
            array( 'handle' => 'color-picker', 'src' => 'color-picker.css' ),
            array( 'handle' => 'media-streamer', 'src' => 'media-streamer.css' ),

            array( 'front' => true, 'handle' => 'faster-slider', 'src' => 'faster-slider.min.css' )
        ),
        'dev_scripts' => array(
            array( 'front' => true, 'handle' => 'faster-slider-js', 'src' => 'faster-slider.js', 'in_footer' => true ),

            array( 'handle' => 'angular', 'src' => 'vendor/angular.js' ),
            array( 'handle' => 'angular-ui', 'src' => 'vendor/angular-ui.min.js', 'deps' => 'angular' ),
            array( 'handle' => 'angular-route', 'src' => 'vendor/angular-route.js', 'deps' => 'angular-ui' ),
            array( 'handle' => 'debugger', 'src' => 'general/debugger.js', 'in_footer' => true ),
            array( 'handle' => 'post-box', 'src' => 'general/post-box.js', 'in_footer' => true ),
            array( 'handle' => 'router', 'src' => 'general/router.js', 'deps' => 'angular-route', 'in_footer' => true ),
            array( 'handle' => 'index-controller', 'src' => 'index/controller.js', 'in_footer' => true ),
            array( 'handle' => 'index-factory', 'src' => 'index/factory.js', 'in_footer' => true ),
            array( 'handle' => 'request', 'src' => 'general/request.js', 'in_footer' => true ),
            array( 'handle' => 'loader', 'src' => 'general/loader.js', 'in_footer' => true ),
            array( 'handle' => 'slider-controller', 'src' => 'slider/controller.js', 'in_footer' => true ),
            array( 'handle' => 'slider-factory', 'src' => 'slider/factory.js', 'in_footer' => true ),
            array( 'handle' => 'slider-directives', 'src' => 'slider/directives.js', 'in_footer' => true ),
            array( 'handle' => 'tools', 'src' => 'general/tools.js', 'in_footer' => true ),
            array( 'handle' => 'notifications', 'src' => 'general/notifications.js', 'in_footer' => true ),

            array( 'handle' => 'media-streamer-factory', 'src' => 'media-streamer/factory.js', 'in_footer' => true ),
            array( 'handle' => 'media-streamer-controller', 'src' => 'media-streamer/controller.js', 'in_footer' => true ),
            array( 'handle' => 'media-streamer-directives', 'src' => 'media-streamer/directives.js', 'in_footer' => true ),

            array( 'handle' => 'slides-controller', 'src' => 'slides/controller.js', 'in_footer' => true ),
            array( 'handle' => 'slides-factory', 'src' => 'slides/factory.js', 'in_footer' => true ),
            array( 'handle' => 'color-picker-js', 'src' => 'vendor/bootstrap-colorpicker-module.min.js', 'in_footer' => true )
        ),
        'production_scripts' => array(
            array( 'front' => true, 'handle' => 'faster-slider-js', 'src' => 'faster-slider.min.js', 'in_footer' => true ),

            array( 'handle' => 'angular', 'src' => 'vendor/angular.js' ),
            array( 'handle' => 'angular-ui', 'src' => 'vendor/angular-ui.min.js', 'deps' => 'angular' ),
            array( 'handle' => 'angular-route', 'src' => 'vendor/angular-route.js', 'deps' => 'angular-ui' ),
            array( 'handle' => 'debugger', 'src' => 'general/debugger.js', 'in_footer' => true ),
            array( 'handle' => 'post-box', 'src' => 'general/post-box.js', 'in_footer' => true ),
            array( 'handle' => 'router', 'src' => 'general/router.js', 'deps' => 'angular-route', 'in_footer' => true ),
            array( 'handle' => 'index-controller', 'src' => 'index/controller.js', 'in_footer' => true ),
            array( 'handle' => 'index-factory', 'src' => 'index/factory.js', 'in_footer' => true ),
            array( 'handle' => 'request', 'src' => 'general/request.js', 'in_footer' => true ),
            array( 'handle' => 'loader', 'src' => 'general/loader.js', 'in_footer' => true ),
            array( 'handle' => 'slider-controller', 'src' => 'slider/controller.js', 'in_footer' => true ),
            array( 'handle' => 'slider-factory', 'src' => 'slider/factory.js', 'in_footer' => true ),
            array( 'handle' => 'slider-directives', 'src' => 'slider/directives.js', 'in_footer' => true ),
            array( 'handle' => 'tools', 'src' => 'general/tools.js', 'in_footer' => true ),
            array( 'handle' => 'notifications', 'src' => 'general/notifications.js', 'in_footer' => true ),

            array( 'handle' => 'media-streamer-factory', 'src' => 'media-streamer/factory.js', 'in_footer' => true ),
            array( 'handle' => 'media-streamer-controller', 'src' => 'media-streamer/controller.js', 'in_footer' => true ),
            array( 'handle' => 'media-streamer-directives', 'src' => 'media-streamer/directives.js', 'in_footer' => true ),

            array( 'handle' => 'slides-controller', 'src' => 'slides/controller.js', 'in_footer' => true ),
            array( 'handle' => 'slides-factory', 'src' => 'slides/factory.js', 'in_footer' => true ),
            array( 'handle' => 'color-picker-js', 'src' => 'vendor/bootstrap-colorpicker-module.min.js', 'in_footer' => true )
        )
    );

    static $FRONT = array(
        'dir_name' => 'front',
        'js_dir_name' => 'scripts',
        'css_dir_name' => 'css',
        'templates_dir_name' => 'templates',
        'template_filename' => 'faster-slider.php',
        'dev_styles' => array(
            array( 'handle' => 'faster-slider-css', 'src' => 'faster-slider.css' )
        ),
        'production_styles' => array(
            array( 'handle' => 'faster-slider-css', 'src' => 'faster-slider.min.css' )
        ),
        'dev_scripts' => array(
            array( 'handle' => 'faster-slider-js', 'src' => 'faster-slider.js' )
        ),
        'production_scripts' => array(
            array( 'handle' => 'faster-slider-js', 'src' => 'faster-slider.min.js' )
        ),
        'plugin_loaded' => false,
        'icons' => array(
            'arrows' => array(
                array( 'left' => '&#xe80f;','right' => '&#xe80e;' ),
                array( 'left' => '&#xe803;','right' => '&#xe802;' ),
                array( 'left' => '&#xe807;','right' => '&#xe806;' ),
                array( 'left' => '&#xe809;','right' => '&#xe808;' ),
                array( 'left' => '&#xe801;','right' => '&#xe800;' )
            ),
            'bullets' => array('&#xe811;','&#xe810;','&#xe812;','&#xe80d;','&#xe80c;')
        )
    );

    static $MENU = array();

    static $CAPABILITY = 'manage_options';//null; // Set on run-time

    static $EFFECTS = array('default','carousel','scaleout','scalein','moveout','rotate3d');

}