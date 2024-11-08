<?php

namespace RnbLite;

/**
 * Handle cart page
 *
 * @version 1.0.0
 * @since 1.0.0
 */

class Product_Cart
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_filter('woocommerce_add_cart_item_data', [$this, 'rnb_lite_add_cart_item_data'], 10, 2);
        add_filter('woocommerce_add_cart_item', [$this, 'rnb_lite_add_cart_item'], 10, 1);
        add_filter('woocommerce_get_cart_item_from_session', [$this, 'rnb_lite_get_cart_item_from_session'], 10, 2);
        add_filter('woocommerce_cart_item_quantity', [$this, 'redq_cart_item_quantity'], 10, 2);
        add_filter('woocommerce_get_item_data', [$this, 'rnb_lite_get_item_data'], 10, 2);
        add_action('woocommerce_new_order_item', [$this, 'rnb_lite_order_item_meta'], 20, 3);
    }

    /**
     * Insert posted data into cart item meta
     *
     * @param  string $product_id , array $cart_item_meta
     * @return array
     */
    public function rnb_lite_add_cart_item_data($cart_item_meta, $product_id)
    {
        $product_type = wc_get_product($product_id)->get_type();
        if (isset($product_type) && $product_type === 'redq_rental' && !isset($cart_item_meta['rental_data']['quote_id'])) {
            $posted_data                   = $this->get_posted_data($product_id, $_POST);
            $cart_item_meta['rental_data'] = $posted_data;
        }
        return $cart_item_meta;
    }

    /**
     * Add cart item meta
     *
     * @param  array $cart_item
     * @return array
     */
    public function rnb_lite_add_cart_item($cart_item)
    {
        if (isset($cart_item['rental_data']['rental_days_and_costs']['cost'])) {
            $cart_item['data']->set_price($cart_item['rental_data']['rental_days_and_costs']['cost']);
        }
        return $cart_item;
    }

    /**
     * Get item data from session
     *
     * @param  array $cart_item
     * @return array
     */
    public function rnb_lite_get_cart_item_from_session($cart_item, $values)
    {
        if (!empty($values['rental_data'])) {
            $cart_item = $this->rnb_lite_add_cart_item($cart_item);
        }
        return $cart_item;
    }

    /**
     * Set quanlity always 1
     *
     * @param  array $cart_item_key , int $product_quantity
     * @return int
     */
    public function redq_cart_item_quantity($product_quantity, $cart_item_key)
    {
        global $woocommerce;
        $cart_details = $woocommerce->cart->cart_contents;
        if (isset($cart_details)) {
            foreach ($cart_details as $key => $value) {
                if ($key === $cart_item_key) {
                    $product_id   = $value['product_id'];
                    $product_type = wc_get_product($product_id)->get_type();
                    if ($product_type === 'redq_rental') {
                        return 1;
                    } else {
                        return $product_quantity;
                    }
                }
            }
        }
    }

    /**
     * Show cart item data in cart and checkout page
     *
     * @param  blank array $custom_data , array $cart_item
     * @return array
     */
    public function rnb_lite_get_item_data($custom_data, $cart_item)
    {
        $product_id   = $cart_item['data']->get_id();
        $product_type = wc_get_product($product_id)->get_type();

        if (isset($product_type) && $product_type === 'redq_rental') {

            $rental_data = $cart_item['rental_data'];

            if (isset($rental_data) && !empty($rental_data)) {
                if (isset($rental_data['pickup_date'])) {
                    $custom_data[] = [
                        'name'    => __('Pickup Date', 'rnb-lite'),
                        'value'   => $rental_data['pickup_date'],
                        'display' => ''
                    ];
                }

                if (isset($rental_data['pickup_time'])) {
                    $custom_data[] = [
                        'name'    => __('Pickup Time', 'rnb-lite'),
                        'value'   => $rental_data['pickup_time'],
                        'display' => ''
                    ];
                }

                if (isset($rental_data['dropoff_date'])) {
                    $custom_data[] = [
                        'name'    => __('Dropoff Date', 'rnb-lite'),
                        'value'   => $rental_data['dropoff_date'],
                        'display' => ''
                    ];
                }

                if (isset($rental_data['dropoff_time'])) {
                    $custom_data[] = [
                        'name'    => __('DropOff Time', 'rnb-lite'),
                        'value'   => $rental_data['dropoff_time'],
                        'display' => ''
                    ];
                }

                if (isset($rental_data['rental_days_and_costs'])) {
                    if ($rental_data['rental_days_and_costs']['days'] > 0) {
                        $custom_data[] = [
                            'name'    => __('Total Days', 'rnb-lite'),
                            'value'   => $rental_data['rental_days_and_costs']['days'],
                            'display' => ''
                        ];
                    } else {
                        $custom_data[] = [
                            'name'    => __('Total Hours', 'rnb-lite'),
                            'value'   => $rental_data['rental_days_and_costs']['hours'],
                            'display' => ''
                        ];
                    }
                }
            }
        }

        return $custom_data;
    }

    /**
     * order_item_meta function
     *
     * @param  string $item_id , array $values
     * @return array
     */
    public function rnb_lite_order_item_meta($item_id, $values, $order_id)
    {
        $item_data = $values->get_data();

        if (!isset($item_data['product_id']) || empty($item_data['product_id'])) {
            return;
        }

        $product_id   = $item_data['product_id'];
        $product_type = wc_get_product($product_id)->get_type();
        if ($product_type !== 'redq_rental' || !isset($values->legacy_values['rental_data'])) {
            return;
        }

        $rental_data = $values->legacy_values['rental_data'];

        //Check for backend order
        if (empty($rental_data)) {
            return;
        }

        if (isset($rental_data['pickup_date'])) {
            wc_add_order_item_meta($item_id, 'Pickup Date', $rental_data['pickup_date']);
        }
        if (isset($rental_data['pickup_time'])) {
            wc_add_order_item_meta($item_id, 'Pickup Time', $rental_data['pickup_time']);
        }
        if (isset($rental_data['dropoff_date'])) {
            wc_add_order_item_meta($item_id, 'Drop-off Date', $rental_data['dropoff_date']);
        }
        if (isset($rental_data['dropoff_time'])) {
            wc_add_order_item_meta($item_id, 'Drop-off Time', $rental_data['dropoff_time']);
        }

        if (isset($rental_data['rental_days_and_costs'])) {
            if ($rental_data['rental_days_and_costs']['days'] > 0) {
                wc_add_order_item_meta($item_id, 'Total Days', $rental_data['rental_days_and_costs']['days']);
            } else {
                wc_add_order_item_meta($item_id, 'Total Hours', $rental_data['rental_days_and_costs']['hours']);
            }
        }

        // update rental availability
        $rental_availability = get_post_meta($values['product_id'], 'rnb_lite_availability', true);
        $date_format         = 'm/d/Y';
        $choose_euro_format  = 'no';

        if ($choose_euro_format === 'no') {

            $pdate = $rental_data['pickup_date'];
            $ddate = $rental_data['dropoff_date'];
            $ptime = $rental_data['pickup_time'];
            $dtime = $rental_data['dropoff_time'];

            $formated_pickup_time  = date("H:i", strtotime($ptime));
            $formated_dropoff_time = date("H:i", strtotime($dtime));
            $pickup_date_time      = strtotime("$pdate $formated_pickup_time");
            $dropoff_date_time     = strtotime("$ddate $formated_dropoff_time");

            $hours       = abs($pickup_date_time - $dropoff_date_time) / (60 * 60);
            $total_hours = 0;

            if ($hours < 24) {
                $days        = 0;
                $total_hours = ceil($hours);
            } else {
                $days        = intval($hours / 24);
                $extra_hours = ceil($hours) % 24;
                if ($extra_hours > floatval($rental_data['max_hours_late'])) {
                    $ara = [
                        'type'     => 'custom_date',
                        'from'     => date($date_format, strtotime($rental_data['pickup_date'])),
                        'to'       => date($date_format, strtotime($rental_data['dropoff_date'])),
                        'rentable' => 'no'
                    ];
                } else {
                    $ara = [
                        'type'     => 'custom_date',
                        'from'     => date($date_format, strtotime($rental_data['pickup_date'])),
                        'to'       => date($date_format, strtotime($rental_data['dropoff_date'] . '-1 day')),
                        'rentable' => 'no'
                    ];
                }

                array_push($rental_availability, $ara);
            }
        }

        if (isset($rental_availability)) {
            update_post_meta($values['product_id'], 'rnb_lite_availability', $rental_availability);
        }
    }

    /**
     * Return all post data for rental
     *
     * @param  string $product_id , array $posted_data
     * @return array
     */
    public function get_posted_data($product_id, $posted_data)
    {
        $data = [];

        if (isset($posted_data['pickup_date']) && !empty($posted_data['pickup_date'])) {
            $data['pickup_date'] = $posted_data['pickup_date'];
        }

        if (isset($posted_data['pickup_time']) && !empty($posted_data['pickup_time'])) {
            $data['pickup_time'] = $posted_data['pickup_time'];
        }

        if (isset($posted_data['dropoff_date']) && !empty($posted_data['dropoff_date'])) {
            $data['dropoff_date'] = $posted_data['dropoff_date'];
        }

        if (isset($posted_data['dropoff_time']) && !empty($posted_data['dropoff_time'])) {
            $data['dropoff_time'] = $posted_data['dropoff_time'];
        }

        $cost_calculation              = $this->calculate_cost($product_id, $data);
        $data['rental_days_and_costs'] = $cost_calculation;
        $data['max_hours_late']        = get_post_meta($product_id, 'redq_max_time_late', true);

        return $data;
    }

    /**
     * Return rental cost and days
     *
     * @param  string $key , array $data
     * @return array
     */
    public function calculate_cost($product_id, $data)
    {
        $all_rental_data        = get_post_meta($product_id, 'redq_all_data', true);
        $calculate_cost_and_day = [];

        if (isset($data['pickup_date'])) {
            $pickup_date  = $data['pickup_date'];
        } else {
            $pickup_date = '';
        }

        if (isset($data['pickup_time'])) {
            $pickup_time  = $data['pickup_time'];
        } else {
            $pickup_time = '';
        }

        if (isset($data['dropoff_date'])) {
            $dropoff_date = $data['dropoff_date'];
        } else {
            $dropoff_date = '';
        }

        if (isset($data['dropoff_time'])) {
            $dropoff_time = $data['dropoff_time'];
        } else {
            $dropoff_time = '';
        }

        $days                            = $this->calculate_rental_days($data);
        $calculate_cost_and_day['days']  = $days['days'];
        $calculate_cost_and_day['hours'] = $days['hours'];
        $pricing_type                    = $all_rental_data['pricing_type'];

        if ($pricing_type === 'general_pricing') {
            $general_pricing = $all_rental_data['general_pricing'];
            $hourly_pricing  = $all_rental_data['hourly_pricing'];
            $cost            = $this->calculate_general_pricing_plan_cost($general_pricing, $days, $hourly_pricing);
        }

        $calculate_cost_and_day['cost'] = $cost;

        return $calculate_cost_and_day;
    }

    /**
     * Calculate total rental days
     *
     * @param  array $data
     * @return string
     */
    public function calculate_rental_days($data)
    {
        $durations = [];

        if (isset($data['pickup_date'])) {
            $pickup_date  = $data['pickup_date'];
        } else {
            $pickup_date = '';
        }
        if (isset($data['dropoff_date'])) {
            $dropoff_date = $data['dropoff_date'];
        } else {
            $dropoff_date = '';
        }

        if (isset($data['pickup_time'])) {
            $pickup_time  = $data['pickup_time'];
        } else {
            $pickup_time = '';
        }
        if (isset($data['dropoff_time'])) {
            $dropoff_time = $data['dropoff_time'];
        } else {
            $dropoff_time = '';
        }

        $formated_pickup_time  = date("H:i", strtotime($pickup_time));
        $formated_dropoff_time = date("H:i", strtotime($dropoff_time));
        $pickup_date_time      = strtotime("$pickup_date $formated_pickup_time");
        $dropoff_date_time     = strtotime("$dropoff_date $formated_dropoff_time");
        $hours                 = abs($pickup_date_time - $dropoff_date_time) / (60 * 60);
        $total_hours           = 0;

        if ($hours < 24) {
            $days        = 0;
            $total_hours = ceil($hours);
        } else {
            $days        = intval($hours / 24);
            $extra_hours = ceil($hours) % 24;
            if ($extra_hours > 1) {  // 1 hour is late static
                $days = $days + 1;
            }
        }

        $durations['days']  = $days;
        $durations['hours'] = $total_hours;

        return $durations;
    }

    /**
     * Calculate general pricing plan's cost
     *
     * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_general_pricing_plan_cost($general_pricing, $durations, $hourly_pricing)
    {
        $days  = $durations['days'];
        $hours = $durations['hours'];

        if ($days > 0) {
            $cost = intval($days) * floatval($general_pricing);
        } else {
            $cost = intval($hours) * floatval($hourly_pricing);
        }
        return $cost;
    }
}

