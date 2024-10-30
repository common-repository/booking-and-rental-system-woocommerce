
<div class="pricing-info">
    <h2><?php esc_html_e('Pricing Info', 'rnb-lite'); ?></h2>
    <h5> <?php esc_html_e('Pricing for', 'rnb-lite'); ?> <?php the_title(); ?> </h5>
    <div class="pricing-info-list">
        <div class="pricing day-based-pricing">
            <h5><?php esc_html_e('General pricing', 'rnb-lite'); ?> : </h5>
            <div class="pricing-plan">
                <?php echo esc_html($currency . $general_pricing); ?> <?php echo esc_html_e('/days', 'rnb-lite'); ?>
            </div>
        </div>
        <div class="pricing hour-based-pricing">
            <h5> <?php esc_html_e('Hourly pricing', 'rnb-lite'); ?> : </h5>
            <div class="pricing-plan">
                <?php echo esc_html($currency . $hourly_pricing); ?> <?php echo esc_html_e('/per hour', 'rnb-lite'); ?>
            </div>
        </div>
    </div>
</div>