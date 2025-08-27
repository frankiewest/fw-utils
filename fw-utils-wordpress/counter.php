<?php
/**
 * Counter shortcode for wordpress
 * 
 * @description Purely for counting from one number to another at a specific speed
 */
add_action('init', function(){
    // Register script
    wp_register_script(
        'fw-counter',
        plugins_url('assets/js/fw-counter.js', __FILE__),
        [],
        '1.0.0',
        true
    );
});

add_shortcode('fw_counter', function($atts) {
    if(is_admin()) return; // bail if admin

    $atts = shortcode_atts([
        'starting_amount' => '0',
        'ending_amount'   => '100',
        'prefix'          => '',
        'suffix'          => '',
        'speed'           => '1500', // milliseconds
    ], $atts, 'fw_counter');

    // Basic sanitization / normalization
    $start = is_numeric($atts['starting_amount']) ? $atts['starting_amount'] + 0 : 0;
    $end   = is_numeric($atts['ending_amount'])   ? $atts['ending_amount'] + 0   : 0;
    $speed = absint($atts['speed']) ?: 1500;
    $prefix = wp_kses_post($atts['prefix']);
    $suffix = wp_kses_post($atts['suffix']);

    // Ensure the shared JS is only loaded once per page render
    wp_enqueue_script('fw-counter');

    // Use data-* attributes so the JS can pick them up
    $html  = '<span class="fw-counter"';
    $html .= ' data-start="' . esc_attr($start) . '"';
    $html .= ' data-end="' . esc_attr($end) . '"';
    $html .= ' data-speed="' . esc_attr($speed) . '"';
    $html .= ' data-prefix="' . esc_attr($prefix) . '"';
    $html .= ' data-suffix="' . esc_attr($suffix) . '"';
    $html .= '></span>';

    return $html;
});