<?php

namespace SeamlessSlider;

class Front {

    static $instance = null;

    static $alias = null;

    private $plugin_index_file = null;

    public function __construct( $plugin_index_file ) {

        $this->plugin_index_file = $plugin_index_file;

        self::$instance = $this;

        Adapter::add_shortcode( 'seamless-slider', array( self::$instance, 'initializePlugin' ));

    }
    public static function initializePlugin( $args ) {

        Config::$FRONT['args'] = array(
            'alias' => $args[0]
        );

        include '' . Config::$FRONT['template_filepath'] . '';

        if( !Config::$FRONT['plugin_loaded'] )
            Config::$FRONT['plugin_loaded'] = true;

    }
    public static function addJS() {

        if( Helper::scriptsExist('FRONT') && !Config::$FRONT['plugin_loaded'] ) {

            foreach( Config::$FRONT[ Config::$DEVELOPMENT_MODE . '_scripts'] as $script_array ) {

                echo file_get_contents( '' . Config::$FRONT['js_dir_path'] . $script_array['src'] . '' );

            }

        }

    }
    public static function addCSS() {

        if( Helper::stylesExist('FRONT') && !Config::$FRONT['plugin_loaded'] ) {

            echo '<style type="text/css">';

            foreach( Config::$FRONT[ Config::$DEVELOPMENT_MODE . '_styles'] as $styles_array ) {

                $contents = file_get_contents( '' . Config::$FRONT['css_dir_path'] . $styles_array['src'] . '' );

                $contents = str_replace( './../../../images/',Config::$PLUGIN['images_dir_url'],$contents );

                $contents = str_replace( './../../../fonts/',Config::$PLUGIN['fonts_dir_url'],$contents );

                echo $contents;

            }

            echo '</style>';

        }

    }

}