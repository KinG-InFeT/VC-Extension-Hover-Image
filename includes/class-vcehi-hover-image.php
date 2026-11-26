<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The Hover Image element. It extends WPBakeryShortCode only for its CSS animation and extra class helpers.
 */
class VCEHI_Hover_Image extends WPBakeryShortCode {

    private const BASE = 'vc_hover_image';
    private const ALIGNMENTS = ['left', 'right', 'center'];
    private const STYLE_HANDLE = 'vc-extension-hover-image';

    // WPBakery's constructor expects the settings it builds for its own elements, so it is not called
    public function __construct() {
        // Registered before vc_map(): WPBakery then keeps this callback instead of adding its own
        add_shortcode(self::BASE, [$this, 'render']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_style_for_content']);

        vc_map($this->element_settings());
    }

    private function element_settings(): array {
        return [
            'name' => __('Hover Image', 'vc-extension-hover-image'),
            'base' => self::BASE,
            'description' => __('Image that changes on mouse hover', 'vc-extension-hover-image'),
            'category' => __('VC Ext Elements', 'vc-extension-hover-image'),
            'params' => [
                [
                    'type' => 'attach_image',
                    'heading' => __('Image', 'vc-extension-hover-image'),
                    'param_name' => 'primary_image',
                    'description' => __('Image shown by default.', 'vc-extension-hover-image'),
                ],
                [
                    'type' => 'attach_image',
                    'heading' => __('Hover image', 'vc-extension-hover-image'),
                    'param_name' => 'hover_image',
                    'description' => __('Image shown when the mouse is over the element. Use an image with the same size as the first one.', 'vc-extension-hover-image'),
                ],
                [
                    'type' => 'vc_link',
                    'heading' => __('Link', 'vc-extension-hover-image'),
                    'param_name' => 'image_link',
                    'description' => __('Leave empty for no link.', 'vc-extension-hover-image'),
                ],
                [
                    'type' => 'dropdown',
                    'heading' => __('Image alignment', 'vc-extension-hover-image'),
                    'param_name' => 'alignment',
                    'value' => [
                        __('Left', 'vc-extension-hover-image') => 'left',
                        __('Right', 'vc-extension-hover-image') => 'right',
                        __('Center', 'vc-extension-hover-image') => 'center',
                    ],
                    'std' => 'left',
                ],
                $this->css_animation_param(),
                [
                    'type' => 'el_id',
                    'heading' => __('Element ID', 'vc-extension-hover-image'),
                    'param_name' => 'el_id',
                    'description' => __('Unique ID of the element, for example to link to it.', 'vc-extension-hover-image'),
                ],
                [
                    'type' => 'textfield',
                    'heading' => __('Extra class name', 'vc-extension-hover-image'),
                    'param_name' => 'el_class',
                    'description' => __('Add a class name to style this element with your own CSS.', 'vc-extension-hover-image'),
                ],
                [
                    'type' => 'css_editor',
                    'heading' => __('CSS box', 'vc-extension-hover-image'),
                    'param_name' => 'css',
                    'group' => __('Design Options', 'vc-extension-hover-image'),
                ],
            ],
        ];
    }

    // WPBakery 9 deprecated vc_map_add_css_animation(), still the only option on older versions bundled with themes
    private function css_animation_param(): array {
        if (function_exists('vc_config') && method_exists(vc_config(), 'get_css_animation')) {
            return vc_config()->get_css_animation();
        }

        return vc_map_add_css_animation();
    }

    // Loaded in the head when the page content has the element, so the two images never show unstyled
    public function enqueue_style_for_content(): void {
        $post = get_post();

        if (is_singular() && $post && has_shortcode($post->post_content, self::BASE)) {
            $this->enqueue_style();
        }
    }

    private function enqueue_style(): void {
        wp_enqueue_style(self::STYLE_HANDLE, VCEHI_PLUGIN_URL . 'css/hover-image.css', [], VCEHI_VERSION);
    }

    public function render($atts): string {
        $atts = shortcode_atts([
            'primary_image' => '',
            'hover_image' => '',
            'image_link' => '',
            'alignment' => 'left',
            'css_animation' => '',
            'el_id' => '',
            'el_class' => '',
            'css' => '',
        ], $atts, self::BASE);

        $image = wp_get_attachment_image((int) $atts['primary_image'], 'full');

        if ($image === '') {
            return '';
        }

        // The hover image repeats the first one, so screen readers skip it
        $images = $image . wp_get_attachment_image((int) $atts['hover_image'], 'full', false, [
            'class' => 'vcehi-hover',
            'alt' => '',
            'aria-hidden' => 'true',
        ]);

        // Also for elements outside the page content (widgets, templates), where the head was already printed
        $this->enqueue_style();

        $alignment = in_array($atts['alignment'], self::ALIGNMENTS, true) ? $atts['alignment'] : 'left';
        $class = 'wpb_single_image wpb_content_element vc_align_' . $alignment
            . $this->getCSSAnimation($atts['css_animation'])
            . vc_shortcode_custom_css_class($atts['css'], ' ')
            . $this->getExtraClass($atts['el_class']);
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- WPBakery's filter, applied as its own elements do
        $class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, self::BASE, $atts);

        $id = $atts['el_id'] !== '' ? ' id="' . esc_attr($atts['el_id']) . '"' : '';

        return '<div' . $id . ' class="' . esc_attr(trim($class)) . '">' . $this->wrap($images, $atts['image_link']) . '</div>';
    }

    private function wrap(string $images, string $image_link): string {
        $link = vc_build_link($image_link);
        // esc_url() drops unsafe schemes such as javascript:
        $url = esc_url($link['url']);

        if ($url === '') {
            return '<span class="vcehi">' . $images . '</span>';
        }

        $attributes = '';
        foreach (['title', 'target', 'rel'] as $name) {
            if ($link[$name] !== '') {
                $attributes .= ' ' . $name . '="' . esc_attr($link[$name]) . '"';
            }
        }

        return '<a class="vcehi" href="' . $url . '"' . $attributes . '>' . $images . '</a>';
    }
}
