<?php

namespace SeamlessSlider;

class Helper {

    public static function adminScripts() {

        Adapter::add_action( 'admin_enqueue_scripts', function() {

        } );

    }
    public static function plugin_page_active() {

        return Helper::get_GET('page') . '.php' === Config::$PLUGIN['filename-slash'];

    }
    public static function addScript( $handle, $src, $deps = false, $ver = null, $in_footer = null ) {

        Adapter::wp_register_script( $handle , $src, $deps, $ver, $in_footer );

        Adapter::wp_enqueue_script( $handle );

    }
    public static function addStyle( $handle, $src, $deps = false,$ver = null,$in_footer = null ) {

        Adapter::wp_register_style( $handle , $src, $deps, $ver, $in_footer );

        Adapter::wp_enqueue_style( $handle );

    }
    public static function setConfig( $plugin_index_file = null ) {

        global $wpdb;

        Config::$IS_MULTISITE = Adapter::is_multisite();

        Config::$TABLE_PREFIX = $wpdb->base_prefix;

        if( Config::$IS_MULTISITE ) {

            global $blog_id;

            Config::$BLOG_ID = $blog_id;

        }

        Config::$PLUGIN['filepath'] = $plugin_index_file;

        Config::$PLUGIN['dir_path'] = dirname( Config::$PLUGIN['filepath'] ) . '/';

        Config::$PLUGIN['dir_name'] = basename( dirname( Config::$PLUGIN['filepath'] ) );

        Config::$PLUGIN['filename'] = basename( Config::$PLUGIN['filepath'] );

        Config::$PLUGIN['filename-slash'] = explode( 'seamless',basename( Config::$PLUGIN['filepath'] ) );

        Config::$PLUGIN['filename-slash'] = 'seamless-' . Config::$PLUGIN['filename-slash'][1];

        Config::$PLUGIN['dir_url'] = Adapter::plugins_url() . '/' . Config::$PLUGIN['dir_name'] . '/';

        Config::$PLUGIN['images_dir_url'] =  Config::$PLUGIN['dir_url'] . Config::$PLUGIN['images_dir_name'] . '/';

        Config::$PLUGIN['icon_url'] = Config::$PLUGIN['images_dir_url'] . Config::$PLUGIN['icon_filename'];

        Config::$PLUGIN['fonts_dir_url'] =  Config::$PLUGIN['dir_url'] . Config::$PLUGIN['fonts_dir_name'] . '/';

        Config::$API['filepath'] = Config::$PLUGIN['dir_path'] . Config::$API['dir_name'] . '/' . Config::$API['filename'];

        Config::$AJAX['url'] = admin_url() . Config::$AJAX['filename'];

        self::setAdminCONFIG();

        self::setFrontCONFIG();
    }
    public static function setAdminCONFIG() {

        Config::$ADMIN['dir_path'] = Config::$PLUGIN['dir_path'] . Config::$ADMIN['dir_name'] . '/';

        Config::$ADMIN['filepath'] = Config::$ADMIN['dir_path'] . Config::$ADMIN['filename'];

        Config::$ADMIN['dir_url'] = Config::$PLUGIN['dir_url'] . Config::$ADMIN['dir_name'] . '/';

        Config::$ADMIN['js_dir_url'] = Config::$ADMIN['dir_url'] . Config::$ADMIN['js_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$ADMIN['css_dir_url'] = Config::$ADMIN['dir_url'] . Config::$ADMIN['css_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$ADMIN['templates_dir_url'] = Config::$ADMIN['dir_url'] . Config::$ADMIN['templates_dir_name'] . '/';

        Config::$ADMIN['JSON'] = str_replace('\\\\','/',
            json_encode( array(
                'images_dir_url' => Config::$PLUGIN['images_dir_url'],
                'templates_dir_url' => Config::$ADMIN['templates_dir_url'],
                'ajax_url' => Config::$AJAX['url'],
                'ajax_validator' => Config::$AJAX['validator'],
                'cache' => Config::$CACHE,
                'debug' => Config::$DEVELOPMENT_MODE === 'dev',
                'icons' => Config::$FRONT['icons'],
                'effects' => Config::$EFFECTS,
            ))
        );

        Config::$MENU[] = array( 'title' => 'Seamless Slider', 'pageFunction' => 'handleAdminPage' );

    }
    public static function setFrontCONFIG() {

        Config::$FRONT['dir_url'] = Config::$PLUGIN['dir_url'] . Config::$FRONT['dir_name'] . '/';

        Config::$FRONT['dir_path'] = Config::$PLUGIN['dir_path'] . Config::$FRONT['dir_name'] . '/';

        Config::$FRONT['template_filepath'] = Config::$FRONT['dir_path'] . Config::$FRONT['templates_dir_name'] . '/' . Config::$FRONT['template_filename'];

        Config::$FRONT['js_dir_path'] = Config::$FRONT['dir_path'] . Config::$FRONT['js_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$FRONT['js_dir_url'] = Config::$FRONT['dir_url'] . Config::$FRONT['js_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$FRONT['css_dir_path'] = Config::$FRONT['dir_path'] . Config::$FRONT['css_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$FRONT['css_dir_url'] = Config::$FRONT['dir_url'] . Config::$FRONT['css_dir_name'] . '/' . Config::$DEVELOPMENT_MODE . '/';

        Config::$FRONT['JSON'] = str_replace('\\\\','/',
            json_encode( array(
                'ajax_url' => Config::$AJAX['url'],
                'ajax_validator' => Config::$AJAX['validator'],
                'icons' => Config::$FRONT['icons'],
                'debug' => Config::$DEVELOPMENT_MODE === 'dev',
                'effects' => Config::$EFFECTS
            ))
        );

    }
    public static function scriptsExist( $CONFIG_NAME = 'ADMIN' ) {

        $CONFIG = Config::$$CONFIG_NAME;

        return isset( $CONFIG[ Config::$DEVELOPMENT_MODE . '_scripts' ] ) && !empty( $CONFIG[ Config::$DEVELOPMENT_MODE . '_scripts'] );

    }
    public static function stylesExist( $CONFIG_NAME = 'ADMIN' ) {

        $CONFIG = Config::$$CONFIG_NAME;

        return isset( $CONFIG[ Config::$DEVELOPMENT_MODE . '_styles'] ) && !empty( $CONFIG[ Config::$DEVELOPMENT_MODE . '_styles'] );

    }
    public static function isAjaxRequest() {

        return defined('DOING_AJAX') && DOING_AJAX;

    }
    public static function get_GET( $name,$default = false ) {

        return self::getVariable( $_GET,$name,$default );

    }
    public static function getVariable( $haystack,$needle,$default ) {

        if( isset( $haystack[ $needle ] ) )
            return $haystack[ $needle ];

        return $default;

    }

}