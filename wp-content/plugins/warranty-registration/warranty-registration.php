<?php
/**
 * Plugin Name: Warranty Registration System
 * Description: Warranty registration with admin full control & customer 
 * Version: 2.2
 * Author: Sree (Developer, Supra)
 */

if (!defined('ABSPATH')) exit;

define('WARRANTY_PATH', plugin_dir_path(__FILE__));
define('WARRANTY_URL', plugin_dir_url(__FILE__));

require_once WARRANTY_PATH . 'includes/cpt-warranty.php';
require_once WARRANTY_PATH . 'includes/ajax/load-warranty-order.php';
//require_once WARRANTY_PATH . 'includes/warranty-status.php';

/* FRONTEND */
require_once WARRANTY_PATH . 'includes/frontend/form-handler.php';
require_once WARRANTY_PATH . 'includes/frontend/my-account-warranties.php';

/* ADMIN */
if (is_admin()) {
    require_once WARRANTY_PATH . 'includes/admin/admin-columns.php';
    require_once WARRANTY_PATH . 'includes/admin/admin-metabox-order.php';
    require_once WARRANTY_PATH . 'includes/admin/admin-order-autofill.php';
    require_once WARRANTY_PATH . 'includes/admin/admin-warranty-form.php';
}

/* FRONTEND ASSETS */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script(
        'warranty-frontend',
        WARRANTY_URL . 'assets/frontend/warranty-frontend.js',
        ['jquery'],
        '2.2',
        true
    );

    wp_localize_script('warranty-frontend', 'warrantyData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('warranty_nonce')
    ]);
});
add_action('admin_init', function () {

    // Remove editor + title support
    remove_post_type_support('warranty', 'editor');
    remove_post_type_support('warranty', 'title');

});
add_action('add_meta_boxes', function () {
    remove_meta_box('submitdiv', 'warranty', 'side');
}, 999);
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
});

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
});
