<?php
namespace RnbLite\Admin;

/**
 * Meta box class
 */
class Product_Meta_Boxes
{
    /**
     * Constructor
     */
    function __construct()
    {
        add_filter('product_type_selector', [$this, 'rnb_lite_product_type']);
        add_filter('woocommerce_product_data_tabs', [$this, 'rnb_lite_additional_tabs']);
        add_action('woocommerce_product_data_panels', [$this, 'rnb_lite_additional_tabs_panel']);
        add_action('woocommerce_process_product_meta', [$this, 'rnb_lite_save_meta']);
        add_action('save_post', [$this, 'redq_save_post']);
    }

    /**
     * Product type added to select dropdown
     *
     * @param array $product_types
     * @return array
     */
    public function rnb_lite_product_type($product_types)
    {
        $product_types['redq_rental'] = __('Rental Product', 'rnb-lite');
        return $product_types;
    }

    /**
     * Rental Product type additional tabs
     *
     * @param array $product_tabs
     * @return array
     */
    public function rnb_lite_additional_tabs($product_tabs)
    {
        $product_tabs['extra_options'] = [
            'label'  => __('Extra Options', 'rnb-lite'),
            'target' => 'extra_options_data',
            'class'  => ['hide_if_grouped', 'show_if_redq_rental', 'hide_if_simple', 'hide_if_external', 'hide_if_variable'],
        ];

        $product_tabs['price_calculation'] = [
            'label'  => __('Price Calculation', 'rnb-lite'),
            'target' => 'price_calculation_product_data',
            'class'  => ['hide_if_grouped', 'show_if_redq_rental', 'hide_if_simple', 'hide_if_external', 'hide_if_variable'],
        ];

        $product_tabs['availability'] = [
            'label'  => __('Availability', 'rnb-lite'),
            'target' => 'availability_product_data',
            'class'  => ['hide_if_grouped', 'show_if_redq_rental', 'hide_if_simple', 'hide_if_external', 'hide_if_variable'],
        ];

        return $product_tabs;
    }

    /**
     * Additional tabs markup
     *
     * @return void
     */
    public function rnb_lite_additional_tabs_panel()
    {
        global $post;
        $post_id = $post->ID;
        include('views/redq-rental-additional-tabs-panel.php');
    }

    /**
     * Save Product/Post
     *
     * @param int $post_id
     * @return void
     */
    public function redq_save_post($post_id)
    {
        $pricing_type = get_post_meta(get_the_ID(), 'pricing_type', true);

        if ($pricing_type == 'general_pricing') {
            $general_pricing = get_post_meta($post_id, 'general_price', true);
            update_post_meta($post_id, '_price', $general_pricing);
        }
    }

    /**
     * Save product meta
     *
     * @param int $post_id
     * @return void
     */
    public function rnb_lite_save_meta($post_id)
    {
        if (isset($_POST['pricing_type'])) {
            update_post_meta($post_id, 'pricing_type', $_POST['pricing_type']);
        }

        if (isset($_POST['general_price'])) {
            update_post_meta($post_id, 'general_price', $_POST['general_price']);
        }

        if (isset($_POST['hourly_price'])) {
            update_post_meta($post_id, 'hourly_price', $_POST['hourly_price']);
        }

        // Own availability checking
        $rental_availability = [];
        if (isset($_POST['rnb_lite_availability_type']) && isset($_POST['rnb_lite_availability_from']) && isset($_POST['rnb_lite_availability_to']) && isset($_POST['redq_availability_rentable'])) {
            $availability_type     = $_POST['rnb_lite_availability_type'];
            $availability_from     = $_POST['rnb_lite_availability_from'];
            $availability_to       = $_POST['rnb_lite_availability_to'];
            $availability_rentable = $_POST['redq_availability_rentable'];
            for ($i = 0; $i < sizeof($availability_type); $i++) {
                $rental_availability[$i]['type']     = $availability_type[$i];
                $rental_availability[$i]['from']     = $availability_from[$i];
                $rental_availability[$i]['to']       = $availability_to[$i];
                $rental_availability[$i]['rentable'] = $availability_rentable[$i];
            }
        }
        if (isset($rental_availability)) {
            update_post_meta($post_id, 'rnb_lite_availability', $rental_availability);
        }

        // General tab data
        $redq_attributes = [];
        if (isset($_POST['redq_attribute_name']) && isset($_POST['redq_attribute_value'])) {
            $attribute_name = $_POST['redq_attribute_name'];
            $attriute_value = $_POST['redq_attribute_value'];
            $attriute_icon  = $_POST['redq_font_awesome_icon'];
            for ($i = 0; $i < sizeof($attribute_name); $i++) {
                $redq_attributes[$i]['name']  = $attribute_name[$i];
                $redq_attributes[$i]['value'] = $attriute_value[$i];
                $redq_attributes[$i]['icon']  = $attriute_icon[$i];
            }
        }
        if (isset($redq_attributes)) {
            update_post_meta($post_id, 'redq_attributes', $redq_attributes);
        }
        if (isset($_POST['redq_feature_name'])) {
            update_post_meta($post_id, 'redq_additional_features', $_POST['redq_feature_name']);
        }

        // save all data
        $redq_booking_data = [];

        $redq_booking_data['pricing_type']    = $_POST['pricing_type'];
        $redq_booking_data['general_pricing'] = $_POST['general_price'];
        $redq_booking_data['hourly_pricing']  = $_POST['hourly_price'];

        if (isset($_POST['block_rental_dates'])) {
            $redq_booking_data['block_rental_dates'] = $_POST['block_rental_dates'];
        }

        if (isset($rental_availability)) {
            $redq_booking_data['rental_availability'] = $rental_availability;
        }
        if (isset($redq_attributes)) {
            $redq_booking_data['attributes'] = $redq_attributes;
        }
        if (isset($_POST['redq_feature_name'])) {
            $redq_booking_data['features'] = $_POST['redq_feature_name'];
        }

        update_post_meta($post_id, 'redq_all_data', $redq_booking_data);
    }
}

