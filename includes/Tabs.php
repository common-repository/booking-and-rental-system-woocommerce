<?php

namespace RnbLite;

/**
 * Hooks Class handler
 */
class Tabs
{
    /**
     * Class initialize
     */
    function __construct()
    {
        add_action('woocommerce_product_tabs', [$this, 'rnb_lite_extra_options_func'], 10);
    }

    public function rnb_lite_extra_options_func($tabs)
    {
        $new_tabs     = [];
        $product_type = wc_get_product(get_the_ID())->get_type();

        if (isset($product_type) && $product_type === 'redq_rental') {
            $item_attributes = get_post_meta(get_the_ID(), 'redq_attributes', true);
            if (is_array($item_attributes) && !empty($item_attributes)) { 
                $tabs['attributes'] = array(
                    'title'    => __('Attributes', 'rnb-lite'),
                    'priority' => 15,
                    'callback' =>  "RnbLite\Tabs::booking_attributes",
                );
            }

            $additional_features = get_post_meta(get_the_ID(), 'redq_additional_features', true);
            if (is_array($additional_features) && !empty($additional_features) ) {
                $tabs['features'] = array(
                    'title'    => __('Additional Features', 'rnb-lite'),
                    'priority' => 18,
                    'callback' => "RnbLite\Tabs::booking_additional_features",
                );
            }
        }

        return $tabs;
    }

    public static function booking_attributes()
    {
        $attributes = get_post_meta(get_the_ID(), 'redq_attributes', true);
        ?>
        <h2><?php esc_html_e('Attributes', 'rnb-lite'); ?></h2>
        <ul>
            <?php foreach ($attributes as  $attribute) {
                ?>
                    <li>
                        <?php if(!empty($attribute['icon'])): ?>
                            <i class="<?php echo esc_attr($attribute['icon']); ?>"></i>
                        <?php endif; ?>
                        <?php echo esc_html($attribute['name']) ?> : <?php echo esc_html($attribute['value']) ?>
                    </li>
                <?php 
            } ?>
        </ul>
        <?php
    }

    public static function booking_additional_features()
    {
        $features   = get_post_meta(get_the_ID(), 'redq_additional_features', true);

        if(!empty($features) && (count($features) > 0)):  ?>
            <h2><?php esc_html_e('Additional Features', 'rnb-lite'); ?></h2>
            <ul>
                <?php foreach ($features as  $feature) {
                    echo '<li>' . esc_html($feature) . '</li>';
                } ?>
            </ul>
        <?php endif;
    }
}