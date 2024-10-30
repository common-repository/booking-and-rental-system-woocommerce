<?php

namespace RnbLite\Admin;

class Menu
{
    /**
     * Init class
     */
    function __construct()
    {
        add_action('admin_menu', [$this, 'rnb_lite_menu']);
    }

    /**
     * rnb_lite_menu
     *
     * @version 1.0.0
     * @since 2.0.4
     */
    public function rnb_lite_menu()
    {
        $page_title = esc_html__('RnB Extensions', 'rnb-lite');
        $menu_title = esc_html__('RnB Extensions', 'rnb-lite');
        $capability = 'publish_posts';
        $menu_slug  = 'rnb_extensions';
        $icon_url   = 'dashicons-plus-alt';

        add_menu_page($page_title, $menu_title, $capability, $menu_slug, [$this, 'rnb_lite_addons_page'], $icon_url, 58);
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
