<?php

/**
 * Plugin Name: VC Extension Hover Image
 * Plugin URI:  https://wordpress-plugins.luongovincenzo.it/#vc-ext-hover-image
 * Description: VC Extension Hover Image is a plugin for WPBakery Page Builder to make a single image with hover
 * Version:     1.2.2
 * Author:      Vincenzo Luongo
 * Author URI:  https://www.luongovincenzo.it/
 * License:     GPL2+
 * Text Domain: vc-ext-hover-image
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

if (!class_exists('WPBakeryShortCode')) {
	return new WP_Error( 'broke', "WPBakery not installed" );
}

class VCExtHoverImage extends WPBakeryShortCode {

	function __construct() {
		add_action('init', array($this, 'vc_ext_hover_image'));
		add_shortcode('vc_hover_image', array($this, 'vc_ext_hover_image_html'));
	}

	public function vc_ext_hover_image() {

        // Stop all if VC is not enabled
		if (!defined('WPB_VC_VERSION')) {
			return;
		}

        // Map the block with vc_map() - https://kb.wpbakery.com/docs/inner-api/vc_map/
		vc_map(
			array(
				'name' => __('Hover Image', 'text-domain'),
				'base' => 'vc_hover_image',
				'description' => __('Add image with hover and click option', 'text-domain'),
				'category' => __('VC Ext Elements', 'text-domain'),
                    //'icon' => plugin_dir_path(__FILE__) . '/assets/img/vc-icon.png',
				'params' => array(
					array(
						'type' => 'attach_image',
						'heading' => __('Add Primary Image', 'appcastle-core'),
						'param_name' => 'primary_image',
						'value' => null,
						'description' => __('Add Primary Image', 'appcastle-core'),
					),
					array(
						'type' => 'attach_image',
						'heading' => __('Add Hover Image', 'appcastle-core'),
						'param_name' => 'hover_image',
						'value' => null,
						'description' => __('Add Hover Image', 'appcastle-core'),
					),
					array(
						'type' => 'vc_link',
						'heading' => __('Add Link', 'appcastle-core'),
						'param_name' => 'image_link',
						'value' => null,
						'description' => __('If empty link, no link', 'appcastle-core'),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Image alignment', 'js_composer'),
						'param_name' => 'alignment',
						'value' => array(
							__('Left', 'js_composer') => 'left',
							__('Right', 'js_composer') => 'right',
							__('Center', 'js_composer') => 'center',
						),
						'description' => __('Select image alignment.', 'js_composer'),
					),
					vc_map_add_css_animation(),
					array(
						'type' => 'el_id',
						'heading' => __('Element ID', 'js_composer'),
						'param_name' => 'el_id',
						'description' => sprintf(__('Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer'), 'http://www.w3schools.com/tags/att_global_id.asp'),
					),
					array(
						'type' => 'textfield',
						'heading' => __('Extra class name', 'js_composer'),
						'param_name' => 'el_class',
						'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer'),
					),
					array(
						'type' => 'css_editor',
						'heading' => __('CSS box', 'js_composer'),
						'param_name' => 'css',
						'group' => __('Design Options', 'js_composer'),
					),
				),
			)
		);
	}

    // Element HTML
	public function vc_ext_hover_image_html($atts) {

		extract(
			shortcode_atts(
				array(
					'title' => '',
					'text' => '',
				),
				$atts
			)
		);

		$primary_image = wp_get_attachment_url($atts['primary_image'], 'full');
		$hover_image = wp_get_attachment_url($atts['hover_image'], 'full');
		$alignment = $atts['alignment'];

		$el_class = @$atts['el_class'];
		$css_animation = @$atts['css_animation'];
		$css = @$atts['css'];

		$url = $atts['image_link'];
		$url = ($url == '||') ? '' : $url;
		$url = vc_build_link($url);
		$link = $url['url'];
		$a_title = ($url['title'] == '') ? '' : 'title="' . $url['title'] . '"';
		$a_target = ($url['target'] == '') ? '' : 'target="' . $url['target'] . '"';

		$class_to_filter = 'wpb_single_image wpb_content_element vc_align_' . $alignment . ' ' . $this->getCSSAnimation($css_animation);
		$class_to_filter .= vc_shortcode_custom_css_class($css, ' ') . $this->getExtraClass($el_class);
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts);

		$html = '<div class="' . esc_attr($css_class) . '">';

		if (!empty($link)) {
			$html .= '<a href="' . $link . '" ' . $a_title . ' ' . $a_target . '>';
		}

		$html .= '<img src="' . $primary_image . '" onMouseover="this.src=\'' . $hover_image . '\'" onMouseout="this.src=\'' . $primary_image . '\'">';

		if (!empty($link)) {
			$html .= '</a>';
		}

		$html .= '</div>';

		return $html;
	}

}

new VCExtHoverImage();
