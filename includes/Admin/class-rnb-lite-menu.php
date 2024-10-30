<?php

if (!defined('ABSPATH')) {
    exit;
}

class RnB_Lite_Menu
{
    /**
     * Init class
     */
    function __construct()
    {
        add_action('admin_menu', array($this, 'rnb_lite_menu'));
    }

    /**
     * rnb_lite_menu
     *
     * @version 1.0.0
     * @since 2.0.4
     */
    public function rnb_lite_menu()
    {
        add_menu_page($page_title = esc_html__('RnB Add-ons', 'rnb-lite'), $menu_title = esc_html__('RnB Add-ons', 'rnb-lite'), $capability = 'publish_posts', $menu_slug = 'rnb_addons', $function = array($this, 'rnb_lite_addons_page'), $icon_url = 'dashicons-plus-alt', 58);
    }

    /**
     * addons page
     *
     * @return void
     */
    public function rnb_lite_addons_page()
    {
        if (!current_user_can('publish_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'rnb-lite'));
        }

        include_once 'views/admin-rnb-addons.php';
    }
}

new RnB_Lite_Menu();
