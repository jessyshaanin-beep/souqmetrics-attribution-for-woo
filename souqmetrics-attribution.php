<?php
/**
 * Plugin Name: SouqMetrics Attribution
 * Description: Captures UTM parameters and click IDs and attaches them to WooCommerce orders for attribution.
 * Version: 1.0.0
 * Author: SouqMetrics
 * Author URI: https://www.souqmetrics.co/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
  exit;
}


/**
 * Capture attribution parameters
 */
add_action('init', function () {
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
    if (!empty($_GET[$key])) {
      $value = sanitize_text_field($_GET[$key]);

      setcookie($key, $value, time() + 30 * DAY_IN_SECONDS, '/');
      $_COOKIE[$key] = $value;
    }
  }

  if (!empty($_SERVER['HTTP_REFERER'])) {
    $referrer = esc_url_raw($_SERVER['HTTP_REFERER']);
    setcookie('souqmetrics_referrer', $referrer, time() + 30 * DAY_IN_SECONDS, '/');
    $_COOKIE['souqmetrics_referrer'] = $referrer;
  }
});

/**
 * Attach attribution data to WooCommerce orders
 */
add_action('woocommerce_checkout_create_order', function ($order) {
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
      $order->update_meta_data($field, sanitize_text_field($_COOKIE[$field]));
    }
  }
});

