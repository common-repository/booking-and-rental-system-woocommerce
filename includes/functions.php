<?php

/**
 * Retrieve all booking data
 *
 * @return array
 */
function rnb_lite_get_booking_data()
{
    return get_post_meta(get_the_ID(), 'redq_all_data', true);
}

/**
 * Calculate Block Dates
 *
 * @return object
 */
function rnb_lite_calculate_block_dates()
{
    $block_dates         = [];
    $block_dates_final   = [];
    $all_data            = get_post_meta(get_the_ID(), 'redq_all_data', true);
    $output_date_format  = 'm/d/Y';
    $rental_availability = get_post_meta(get_the_ID(), 'rnb_lite_availability', true);
    $rental_block        = 'yes';

    if (isset($rental_block) && $rental_block === 'yes') {
        if (isset($rental_availability) && !empty($rental_availability)) {
            foreach ($rental_availability as $key => $value) {
                $all_dates     = rnb_lite_manage_all_dates($value['from'], $value['to'], 'no', $output_date_format);
                $block_dates[] = $all_dates;
            }
        }

        foreach ($block_dates as $block_date) {
            foreach ($block_date as $key => $value) {
                $block_dates_final[] = $value;
            }
        }
    }

    return $block_dates_final;
}

/**
 * Manage all Block Dates
 *
 * @return object
 */
function rnb_lite_manage_all_dates($start_dates, $end_dates, $choose_euro_format, $output_format, $step = '+1 day')
{

    $dates = [];

    if ($choose_euro_format === 'no') {
        $current = strtotime($start_dates);
        $last    = strtotime($end_dates);
    } else {
        $start   = date('Y/m/d', strtotime(str_replace('/', '-', $start_dates)));
        $end     = date('Y/m/d', strtotime(str_replace('/', '-', $end_dates)));
        $current = strtotime($start);
        $last    = strtotime($end);
    }

    while ($current <= $last) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

/**
 * Update price according to pircing type
 *
 * @return null
 */
function rnb_lite_update_prices()
{
    $post_id      = get_the_ID();
    $pricing_type = get_post_meta($post_id, 'pricing_type', true);

    if ($pricing_type == 'general_pricing') {
        $general_pricing = get_post_meta($post_id, 'general_price', true);
        update_post_meta($post_id, '_price', $general_pricing);
    }
}