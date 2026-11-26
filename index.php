<?php
/**
 * Plugin Name: VC Extension Hover Image
 * Plugin URI: https://wordpress-plugins.luongovincenzo.it/plugin/vc-extension-hover-image
 * Description: Adds a Hover Image element to WPBakery Page Builder: an image that changes to a second image on mouse hover, with an optional link.
 * Version: 2.0.0
 * Author: Vincenzo Luongo
 * Author URI: https://www.luongovincenzo.it/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: vc-extension-hover-image
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */
if (!defined('ABSPATH')) {
    exit;
}

define('VCEHI_VERSION', '2.0.0');
define('VCEHI_PLUGIN_URL', plugin_dir_url(__FILE__));

// WPBakery fires this action once it is loaded, whether it is installed as a plugin or bundled with the theme
add_action('vc_before_init', 'vcehi_init');
add_action('admin_notices', 'vcehi_missing_wpbakery_notice');

function vcehi_init(): void {
    if (!class_exists('WPBakeryShortCode') || !function_exists('vc_map')) {
        return;
    }

    require_once __DIR__ . '/includes/class-vcehi-hover-image.php';
    new VCEHI_Hover_Image();
}

function vcehi_missing_wpbakery_notice(): void {
    $screen = get_current_screen();

    if (did_action('vc_before_init') || !$screen || $screen->id !== 'plugins' || !current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>' . esc_html__('VC Extension Hover Image needs WPBakery Page Builder: activate it to use the Hover Image element.', 'vc-extension-hover-image') . '</p></div>';
}
