<?php

namespace RnbLite;

use RnbLite\Traits\Assets_Trait;

/**
 * Assets class handler
 */
class Assets
{

    use Assets_Trait;

    /**
     * Initialize assets
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register_front_assets']);
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);
    }

    /**
     * Register front assets
     */
    public function register_front_assets()
    {
        global $post, $woocommerce, $wp_scripts;

        $scripts = $this->get_front_scripts();
        $styles  = $this->get_front_styles();

        $this->iterate_scripts($scripts);

        $block_dates  = rnb_lite_calculate_block_dates();
        $booking_data = rnb_lite_get_booking_data();
        rnb_lite_update_prices();

        wp_localize_script('cost-handle', 'BOOKING_DATA', [
            'all_data'    => $booking_data,
            'block_dates' => $block_dates
        ]);

        wp_localize_script('main-script', 'BOOKING_DATA', [
            'all_data'    => $booking_data,
            'block_dates' => $block_dates
        ]);

        $this->iterate_styles($styles);
    }

    /**
     * Register admin assets
     */
    public function register_admin_assets()
    {
        global $post, $woocommerce, $wp_scripts;

        $scripts = $this->get_admin_scripts();
        $styles  = $this->get_admin_styles();

        $this->iterate_scripts($scripts);

        wp_enqueue_script('jquery-ui-datepicker');

        wp_localize_script('rnb-lite-writepanel', 'rnb_lite_writepanel_js_params', [
            'post'           => isset($post->ID) ? $post->ID : '',
            'plugin_url'     => $woocommerce->plugin_url(),
            'ajax_url'       => admin_url('admin-ajax.php'),
            'calendar_image' => $woocommerce->plugin_url() . '/assets/images/calendar.png',
            'all_data'       => rnb_lite_get_booking_data(),
        ]);

        $this->iterate_styles($styles);

    }

    /**
     * Iterate scripts
     *
     * @param array $scripts
     * @param string $load_on
     * @return void
     */
    public function iterate_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps    = isset($script['deps']) ? $script['deps'] : false;
            $version = isset($script['version']) ? $script['version'] : RNB_LITE_VERSION;

            wp_register_script($handle, $script['src'], $deps, $version, true);
            wp_enqueue_script($handle);
        }
    }

    /**
     * Iterate styles
     *
     * @param array $styles
     * @param string $load_on
     * @return void
     */
    public function iterate_styles ($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps    = isset($style['deps']) ? $style['deps'] : false;
            $version = isset($style['version']) ? $style['version'] : RNB_LITE_VERSION;

            wp_register_style($handle, $style['src'], $deps, $version);
            wp_enqueue_style($handle);
        }
    }

}