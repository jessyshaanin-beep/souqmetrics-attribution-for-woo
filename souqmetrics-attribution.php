<?php
/**
 * Plugin Name: SouqMetrics Attribution
 * Description: Captures UTM parameters and click IDs and attaches them to WooCommerce orders for attribution.
 * Version: 1.0.0
 * Author: SouqMetrics
 * Author URI: https://www.souqmetrics.co/
 * Requires Plugins: woocommerce
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bail if WooCommerce is not active
 */
if (!class_exists('WooCommerce')) {
    return;
}

/**
 * Capture attribution parameters (frontend only)
 */
add_action('init', function () {

    // Only run on frontend
    if (is_admin()) {
        return;
    }

    $keys = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'fbclid',
        'gclid',
        'ttclid'
    ];

    foreach ($keys as $key) {
        if (isset($_GET[$key])) {

            $value = sanitize_text_field(
                wp_unslash($_GET[$key])
            );

            setcookie(
                $key,
                $value,
                time() + (30 * DAY_IN_SECONDS),
                COOKIEPATH,
                COOKIE_DOMAIN
            );

            $_COOKIE[$key] = $value;
        }
    }

    if (!empty($_SERVER['HTTP_REFERER'])) {

        $referrer = esc_url_raw(
            wp_unslash($_SERVER['HTTP_REFERER'])
        );

        setcookie(
            'souqmetrics_referrer',
            $referrer,
            time() + (30 * DAY_IN_SECONDS),
            COOKIEPATH,
            COOKIE_DOMAIN
        );

        $_COOKIE['souqmetrics_referrer'] = $referrer;
    }

});

/**
 * Attach attribution data to WooCommerce orders
 */
add_action('woocommerce_checkout_create_order', 'souqmetrics_attach_attribution');

function souqmetrics_attach_attribution($order) {

    $fields = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'fbclid',
        'gclid',
        'ttclid',
        'souqmetrics_referrer'
    ];

    foreach ($fields as $field) {
        if (!empty($_COOKIE[$field])) {
            $order->update_meta_data(
                $field,
                sanitize_text_field(
                    wp_unslash($_COOKIE[$field])
                )
            );
        }
    }
}
