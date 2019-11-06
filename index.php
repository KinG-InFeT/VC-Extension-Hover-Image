<?php

/**
 * Plugin Name: VC Extension Hover Image
 * Plugin URI:  https://wordpress-plugins.luongovincenzo.it/#vc-ext-hover-image
 * Description: VC Extension Hover Image is a plugin for WPBakery Page Builder to make a single image with hover
 * Version:     1.0.1
 * Author:      Vincenzo Luongo
 * Author URI:  https://www.luongovincenzo.it/
 * License:     GPL2+
 * Text Domain: vc-ext-hover-image
 */

// Before VC Init
add_action('vc_before_init', 'vc_before_init_actions');

function vc_before_init_actions() {
    require_once( plugin_dir_path(__FILE__) . 'templates/vc_ext_hover_image.php');
}
