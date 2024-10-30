<?php
/**
 * Plugin Name: RnB Lite - WooCommerce Booking and Rental System
 * Plugin URI: http://demo.redq.io/rnb/
 * Description: WooCommerce based rental & plugin. You can create any date or date range based booking with this plugin.
 * Version: 2.1.1
 * Author: RedQ Team
 * Author URI: https://redq.io/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rnb-lite
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.2
 * WC requires at least: 3.0
 * WC tested up to: latest
 */

if (!defined('ABSPATH')) {
    exit;
}

// vendor autoload
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Admin notice about dependency plugin
 */
$rnb_active_plugins   = apply_filters('active_plugins', get_option('active_plugins'));
$rnb_required_plugins = ['woocommerce/woocommerce.php'];

if (count(array_intersect($rnb_required_plugins, $rnb_active_plugins)) !== count($rnb_required_plugins)) {
    add_action('admin_notices', 'rnb_lite_notice');
    function rnb_lite_notice()
    {
        $woocommerce_link = 'https://woocommerce.com/';

        /* translators: %s: Notice about required plugins */
        echo '<div class="error"><p><strong>' . sprintf(__('RnB Lite requires "WooCommerce" to be installed and active. You can download <a href="%s" target="_blank">WooCommerce</a> from here.', 'rnb-lite'), esc_url($woocommerce_link)) . '</strong></p></div>';
    }
    return;
}

/**
 * RnB Lite main class
 */
class RnB_Lite
{
    /**
     * Current Version
     *
     * @var string
     */
    private static $version = '2.1.1';

    /**
     * Plugin data from get_plugins()
     *
     * @since 1.0
     * @var object
     */
    public $plugin_data;

    /**
     * Includes to load
     *
     * @since 1.0
     * @var array
     */
    public $includes;

    /**
     * Plugin Action and Filter Hooks
     *
     * @since 1.0.0
     * @return null
     */
    public function __construct()
    {
        $this->rnb_lite_define_constants();
        register_activation_hook(__FILE__, [$this, 'activation']);

        add_action('plugins_loaded', [$this, 'rnb_lite_set_plugins_data']);
        add_action('plugins_loaded', [$this, 'rnb_lite_includes']);
        add_action('woocommerce_redq_rental_add_to_cart', [$this, 'rnb_lite_add_to_cart'], 30);
        add_action('plugins_loaded', [$this, 'rnb_lite_textdomain']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'rnb_lite_links'], 10, 1);
    }

    /**
     * Set plugins data
     *
     * @return void
     */
    public function rnb_lite_set_plugins_data()
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_dir        = plugin_basename(dirname(__FILE__));
        $plugin_data       = current(get_plugins('/' . $plugin_dir));
        $this->plugin_data = apply_filters('redq_plugin_data', $plugin_data);
    }

    /**
     * Plugin constant define
     *
     * @since 1.0.0
     * @return null
     */
    public function rnb_lite_define_constants()
    {
        define('RNB_LITE_VERSION', self::$version);
        define('RNB_LITE_FILE', __FILE__);
        define('RNB_LITE_DIR', dirname(plugin_basename(RNB_LITE_FILE)));
        define('RNB_LITE_PATH', untrailingslashit(plugin_dir_path(RNB_LITE_FILE)));
        define('RNB_LITE_URL', untrailingslashit(plugin_dir_url(RNB_LITE_FILE)));
        define('RNB_LITE_INC_DIR', 'includes');
        define('RNB_LITE_ASSETS_DIR', 'assets');
        define('RNB_LITE_LANG_DIR', 'languages');
        define('REDQ_ROOT_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
        define('REDQ_PACKAGE_TEMPLATE_PATH', untrailingslashit(plugin_dir_path(__FILE__)) . '/templates/');
    }

    /**
     * Include Files
     *
     * @return void
     */
    public function rnb_lite_includes()
    {
        new RnbLite\Assets();

        if (is_admin()) {
            new RnbLite\Admin();
        } else{
            new RnbLite\Product_Cart();
            new RnbLite\Hooks();
            new RnbLite\Tabs();
        }

        require_once trailingslashit(RNB_LITE_PATH) . RNB_LITE_INC_DIR . '/class-rnb-lite-redq_rental.php';
    }

    /**
     * Do something on plugin activation
     *
     * @return void
     */
    public function activation()
    {
        $installer = new RnbLite\Installer();

        $installer->run();
    }

    /**
     * Add to cart page show in front-end
     *
     * @since 1.0.0
     * @return null
     */
    public function rnb_lite_add_to_cart()
    {
        wc_get_template('single-product/add-to-cart/redq_rental.php', $args = [], $template_path = '', REDQ_PACKAGE_TEMPLATE_PATH);
    }

    /**
     * Support languages
     *
     * @since 1.0.0
     * @return null
     */
    public function rnb_lite_textdomain()
    {
        load_plugin_textdomain('rnb-lite', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Plugin row action links
     *
     * @param array $links
     * @return array
     */
    public function rnb_lite_links($links)
    {
        $links[] = '<a href="https://1.envato.market/vYx3v" target="_blank">'.esc_html__('Get Premium Version', 'rnb-lite').'</a>';

        $links[] = '<a href="https://1.envato.market/NKzXeP" target="_blank">'.esc_html__('Compatible WP Theme', 'rnb-lite').'</a>';

        return $links;
    }

    /**
     * Initialize singleton instance
     *
     * @return \RnB_Lite
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }
}

/**
 * Initialize main plugin
 *
 * @return \RnB_Lite
 */
function rnb_lite()
{
    return RnB_Lite::init();
}

/**
 * Check if RnB Pro is active
 *
 * if pro is active then rnb lite won't be loaded. it will stop execution its features
 */
$is_rnb_pro_active = ['woocommerce-rental-and-booking/redq-rental-and-bookings.php'];
if (count(array_intersect($is_rnb_pro_active, $rnb_active_plugins)) !== count($is_rnb_pro_active)) {
    rnb_lite();
}
