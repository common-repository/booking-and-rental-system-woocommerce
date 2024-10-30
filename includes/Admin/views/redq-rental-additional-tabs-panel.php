<div id="price_calculation_product_data" class="panel woocommerce_options_panel">

    <?php

    woocommerce_wp_select(array('id' => 'pricing_type', 'label' => __('Set Price Type', 'rnb-lite'), 'description' => sprintf(__('Choose a price type - this controls the <a href="%s">schema</a>.', 'rnb-lite'), 'http://schema.org/'), 'options' => array(
        'general_pricing'            => __('General Pricing', 'rnb-lite'),
    )));

    $plugin_uri = 'http://codecanyon.net/item/rnb-woocommerce-rental-booking/14835145?ref=redqteam';
    echo '<div id="message" class="updated inline rental-notice"><p><strong>' . sprintf(__('For More Pricing Plans & Others Amazing Features, Please Visit ' . '<a href="%1$s" target="_blank"> ' . __('Premium Version', 'rnb-lite') . '</a>' . ' Of This Plugin. ', 'rnb-lite'), $plugin_uri) . '</strong></p></div>';

    ?>

    <div class="hourly-pricing-panel show_if_general_pricing">
        <?php
        woocommerce_wp_text_input(array('id' => 'hourly_price', 'label' => __('Hourly Price', 'rnb-lite'), 'placeholder' => __('Enter price here', 'rnb-lite'), 'type' => 'number', 'description' => sprintf(__('Hourly price will be applicabe if booking or rental days min 1day', 'rnb-lite')), 'custom_attributes' => array(
            'step'     => '1',
            'min'    => '0'
        )));
        ?>
    </div>

    <div class="general-pricing-panel show_if_general_pricing">
        <h4 class="redq-headings"><?php _e('Set general pricing plan', 'rnb-lite'); ?></h4>
        <?php
        woocommerce_wp_text_input(array('id' => 'general_price', 'label' => __('General Price', 'rnb-lite'), 'placeholder' => __('Enter price here', 'rnb-lite'), 'type' => 'number', 'custom_attributes' => array(
            'step'     => '1',
            'min'    => '0'
        )));
        ?>
    </div>
</div>

<!-- Availability tab -->
<div id="availability_product_data" class="panel woocommerce_options_panel">
    <h4 class="redq-headings"><?php _e('Product Availabilities', 'rnb-lite') ?></h4>

    <div class="options_group own_availibility">
        <div class="table_grid">
            <table class="widefat">
                <thead style="2px solid #eee;">
                    <tr>
                        <th class="sort" width="1%">&nbsp;</th>
                        <th><?php _e('Range type', 'rnb-lite'); ?></th>
                        <th><?php _e('From', 'rnb-lite'); ?></th>
                        <th><?php _e('To', 'rnb-lite'); ?></th>
                        <th><?php _e('Bookable', 'rnb-lite'); ?>&nbsp;<a class="tips" data-tip="<?php _e('Please select the date range for which you want the product to be disabled.', 'rnb-lite'); ?>">[?]</a></th>
                        <th class="remove" width="1%">&nbsp;</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="6">
                            <a href="#" class="button button-primary add_redq_row" data-row="<?php
                                                                                                ob_start();
                                                                                                include('html-own-availability.php');
                                                                                                $html = ob_get_clean();
                                                                                                echo esc_attr($html);
                                                                                                ?>"><?php _e('Add Dates', 'uou-bookings'); ?></a>
                            <span class="description"><?php _e('Please select the date range to be disabled for the product.', 'uou-bookings'); ?></span>
                        </th>
                    </tr>
                </tfoot>
                <tbody id="availability_rows">
                    <?php

                    $rental_avalability = get_post_meta($post_id, 'rnb_lite_availability', true);

                    if (!empty($rental_avalability) && is_array($rental_avalability)) {
                        foreach ($rental_avalability as $availability) {
                            include('html-own-availability.php');
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>