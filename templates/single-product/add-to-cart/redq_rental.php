<?php

/**
 * Redq rental product add to cart
 *
 * @author 		redq-team
 * @package 	RedqTeam/Templates
 * @version     1.0.0
 * @since       1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $woocommerce, $product;

?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="cart" method="post" enctype='multipart/form-data'>

    <div class="date-time-picker">
        <h5><?php esc_html_e('Pickup Date & Time', 'rnb-lite'); ?></h5>
        <span class="pick-up-date-picker">
            <i class="fa fa-calendar-alt"></i>
            <input type="text" autocomplete="off" name="pickup_date" id="pickup-date" placeholder="<?php esc_attr_e('Pick-up date', 'rnb-lite') ?>" value="">
        </span>
        <span class="pick-up-time-picker">
            <i class="fa fa-clock"></i>
            <input type="text" autocomplete="off" name="pickup_time" id="pickup-time" placeholder="<?php esc_attr_e('Pick-up time', 'rnb-lite') ?>" value="">
        </span>
    </div>

    <div class="date-time-picker">
        <h5><?php esc_html_e('Drop-off Date & Time', 'rnb-lite'); ?></h5>
        <span class="drop-off-date-picker">
            <i class="fa fa-calendar-alt"></i>
            <input type="text" autocomplete="off" name="dropoff_date" id="dropoff-date" placeholder="<?php esc_attr_e('Drop-off date', 'rnb-lite'); ?>" value="">
        </span>
        <span class="drop-off-time-picker">
            <i class="fa fa-clock"></i>
            <input type="text" autocomplete="off" name="dropoff_time" id="dropoff-time" placeholder="<?php esc_attr_e('drop-up time', 'rnb-lite'); ?>" value="">
        </span>
    </div>

    <?php do_action('rnb_lite_extra_options'); ?>

    <input type="hidden" name="currency-symbol" class="currency-symbol" value="<?php echo get_woocommerce_currency_symbol(); ?>">

    <h3 class="booking_cost" style="display: none"><?php esc_html_e('Total Booking Cost : ', 'rnb-lite'); ?><span style="float: right;"></span></h3>

    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" />
    <button style="margin-top: 15px" type="submit" class="single_add_to_cart_button rnb_single_add_to_cart_button btn-book-now button alt"><?php esc_html_e('Book Now', 'rnb-lite'); ?></button>
    <?php do_action('woocommerce_after_add_to_cart_button'); ?>

</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>