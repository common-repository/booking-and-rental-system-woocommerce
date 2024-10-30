<?php

namespace RnbLite;

/**
 * Hooks Class handler
 */
class Hooks
{
    /**
     * Class initialize
     */
    function __construct()
    {
        add_action('woocommerce_single_product_summary', [$this, 'rnb_lite_price_info_box'], 11);
        add_filter('woocommerce_get_price_html', [$this, 'add_suffix_to_product_price'], 10, 2);
    }

    /**
     * Pricing Info
     *
     * @return void
     */
    public function rnb_lite_price_info_box(){
        $product_id   = get_the_ID();
        $product_type = wc_get_product($product_id)->get_type();
        if ($product_type != 'redq_rental') return;

        $pricing_type = get_post_meta($product_id, 'pricing_type', true);

        if ($pricing_type == 'general_pricing') {
            $currency        = get_woocommerce_currency_symbol();
            $general_pricing = get_post_meta($product_id, 'general_price', true);
            $general_pricing = $general_pricing ? $general_pricing : 0;
            $hourly_pricing  = get_post_meta($product_id, 'hourly_price', true);
            $hourly_pricing  = $hourly_pricing ? $hourly_pricing : 0;

            include_once 'views/pricing-info.php';
        }

    }

    /**
     * Suffix with product price
     *
     * @param string $price
     * @param object $product
     * @return float
     */
    public function add_suffix_to_product_price($price, $product) {
        $product_type = $product->get_type();
        if ($product_type != 'redq_rental') return $price;

        // Add your desired suffix text here
        $suffix_text = __('/day', 'rnb-lite');

        // Append the suffix to the price
        $price .= ' ' . $suffix_text;

        return $price;
    }

}