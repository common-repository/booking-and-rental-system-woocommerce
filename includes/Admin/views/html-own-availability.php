<?php

if (!isset($availability['type']))
    $availability['type'] = 'custom_date';
?>

<tr>
    <td class="sort">&nbsp;</td>
    <td>
        <div class="select rental_availability_type">
            <select name="rnb_lite_availability_type[]">
                <option value="custom_date" selected="selected"><?php esc_html_e('Custom date range', 'rnb-lite'); ?></option>
            </select>
        </div>
    </td>
    <td>
        <div class="from_date">
            <input type="text" style="border: 1px solid #ddd;" class="date-picker" name="rnb_lite_availability_from[]" value="<?php if (!empty($availability['from'])) echo esc_attr($availability['from']); ?>" />
        </div>
    </td>
    <td>
        <div class="to_date">
            <input type="text" style="border: 1px solid #ddd;" class="date-picker" name="rnb_lite_availability_to[]" value="<?php if (!empty($availability['to'])) echo esc_attr($availability['to']); ?>" />
        </div>
    </td>
    <td>
        <div class="select">
            <select name="redq_availability_rentable[]">
                <option value="no" <?php selected(isset($availability['rentable']) && $availability['rentable'] == 'no', true) ?>><?php esc_html_e('Not', 'rnb-lite'); ?></option>
                <!-- <option value="yes" <?php selected(isset($availability['bookable']) && $availability['bookable'] == 'yes', true) ?>><?php esc_html_e('Yes', 'rnb-lite'); ?></option> -->
            </select>
        </div>
    </td>
    <td class="remove"><button type="btn" class="btn"><?php esc_html_e('delete', 'rnb-lite'); ?></button></td>
</tr>