<?php

namespace SeamlessSlider;

class Admin {

    static $instance = null;

    public function __construct( $plugin_index_file ) {

        self::$instance = $this;

        if( Helper::plugin_page_active() ) {

            DBUpdater::getInstance()->prepare()->handleUpdate();

            Adapter::add_action( 'admin_enqueue_scripts', array( self::$instance, 'addCSS' ) );

            Adapter::add_action( 'admin_enqueue_scripts', array( self::$instance, 'addJS' ) );

        }

        Adapter::add_action( 'admin_menu', array( self::$instance, 'extendMenu') );

        Adapter::register_activation_hook( Config::$PLUGIN['filepath'], array( DBUpdater::getInstance()->prepare(), 'handleActivation' ) );

    }
    public static function handleAdminPage() {

        include '' . Config::$ADMIN['filepath'] . '';

    }
    public static function extendMenu() {

        foreach( Config::$MENU as $menu_item ) {

            $title = $menu_item["title"];

            $pageFunctionName = $menu_item["pageFunction"];

            Adapter::add_menu_page( $title, $title, Config::$CAPABILITY, Config::$PLUGIN['dir_path'], array( self::$instance, $pageFunctionName), Config::$PLUGIN['icon_url'] );

        }

    }
    public static function addJS() {

        Adapter::wp_enqueue_media();

        if( Helper::scriptsExist() ) {

            foreach( Config::$ADMIN[ Config::$DEVELOPMENT_MODE . '_scripts' ] as $script_array ) {

                $deps = isset( $script_array['deps'] ) ? $script_array['deps'] : null;
                $ver = null;
                $in_footer = isset( $script_array['in_footer'] ) ? $script_array['in_footer'] : null;

                if( !isset( $script_array['front'] ) || !$script_array['front'] )
                    Helper::addScript( $script_array['handle'], Config::$ADMIN['js_dir_url'] . $script_array['src'], $deps, $ver, $in_footer );
                else
                    Helper::addScript( $script_array['handle'], Config::$FRONT['js_dir_url'] . $script_array['src'], $deps, $ver, $in_footer );


            }

        }

    }
    public static function addCSS() {

        if( Helper::stylesExist() ) {

            foreach( Config::$ADMIN[ Config::$DEVELOPMENT_MODE . '_styles' ] as $styles_array ) {

                $deps = isset( $styles_array['deps'] ) ? $styles_array['deps'] : null;
                $ver = null;
                $in_footer = isset( $styles_array['in_footer'] ) ? $styles_array['in_footer'] : null;

                if( !isset( $styles_array['front'] ) || !$styles_array['front'] )
                    Helper::addStyle( $styles_array['handle'], Config::$ADMIN['css_dir_url'] . $styles_array['src'], $deps, $ver, $in_footer );
                else
                    Helper::addStyle( $styles_array['handle'], Config::$FRONT['css_dir_url'] . $styles_array['src'], $deps, $ver, $in_footer );



            }
        }
    }
}