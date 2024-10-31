<?php

namespace SeamlessSlider;

class Adapter {

    public static function add_action( $name,$fn ) {

        add_action( $name, $fn );

    }
    public static function register_widget( $name ) {

        register_widget( $name );

    }
    public static function add_shortcode( $name,$fn ) {

        add_shortcode( $name, $fn );

    }
    public static function is_multisite() {

        return is_multisite();

    }
    public static function is_admin() {

        return is_admin();

    }
    public static function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = null, $icon_url = null, $position = null ) {

        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    }
    public static function register_activation_hook( $file, $fn ) {

        register_activation_hook( $file, $fn );

    }
    public static function wp_register_script( $handle, $src, $deps = null, $ver = null, $in_footer = null ) {

        wp_register_script( $handle, $src, $deps, $ver, $in_footer );

    }
    public static function wp_enqueue_script( $handle, $src = null, $deps = null, $ver = null, $in_footer = null ) {

        wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

    }
    public static function plugins_url() {

        return plugins_url();

    }
    public static function wp_register_style( $handle, $src, $deps = null, $ver = null, $media = null ) {

        wp_register_style( $handle, $src, $deps, $ver, $media );

    }
    public static function wp_enqueue_style( $handle, $src = null, $deps = null, $ver = null, $media = null ) {

        wp_enqueue_style( $handle, $src, $deps, $ver, $media );

    }
    public static function wp_enqueue_media() {

        wp_enqueue_media();

    }
    public static function current_user_can( $name ) {

        return current_user_can( $name );

    }
    public static function register_uninstall_hook( $file, $callback ) {

        register_uninstall_hook( $file, $callback );

    }

}