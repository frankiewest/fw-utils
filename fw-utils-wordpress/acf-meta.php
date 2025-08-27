<?php
/**
 * ACF/meta field shortcode
 * 
 * @description Printing value of ACF / meta custom field
 */
function fw_acf_meta_shortcode( $atts ) {
    // Set default shortcode attributes and merge with user-supplied attributes.
    // 'field' is the meta/ACF field name, 'id' is the post/user ID, 'type' is 'post' or 'user'.
    $atts = shortcode_atts( array(
        'field' => '',
        'id'    => '',
        'type'  => 'post', // 'post' or 'user'
    ), $atts, 'acf_meta' );

    // If no ID is provided, determine the ID based on type.
    // For 'user', use the current user ID. For 'post', use the current post ID.
    if ( empty( $atts['id'] ) ) {
        $atts['id'] = ( $atts['type'] === 'user' ) ? get_current_user_id() : get_the_ID();
    }

    // For user meta, ACF expects the ID to be prefixed with 'user_'.
    // For posts, use the ID as is.
    $object_id = ( $atts['type'] === 'user' ) ? 'user_' . $atts['id'] : $atts['id'];

    // Attempt to get the field value using ACF's get_field() if available.
    // If ACF is not active, $value will be false.
    $value = function_exists('get_field') ? get_field( $atts['field'], $object_id ) : false;

    // If ACF did not return a value, fall back to WordPress core meta functions.
    // For users, use get_user_meta(). For posts, use get_post_meta().
    if ( empty( $value ) ) {
        if ( $atts['type'] === 'user' ) {
            $value = get_user_meta( $atts['id'], $atts['field'], true );
        } else {
            $value = get_post_meta( $atts['id'], $atts['field'], true );
        }
    }

    // If a value was found, output it wrapped in a span with appropriate classes.
    // The class includes 'fw-acf-meta' and the field name for styling.
    if ( $value ) {
        $class = 'fw-acf-meta ' . esc_attr( $atts['field'] );
        return '<span class="' . $class . '">' . esc_html( $value ) . '</span>';
    }

    // If no value was found, return an empty string.
    return '';
}