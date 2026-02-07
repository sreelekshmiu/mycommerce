<?php
/**
 * Plugin Name: Warranty Registration System
 * Description: Warranty registration with admin full control & customer view-only
 * Version: 2.1
 * Author: Sree (Developer, Supra)
 */

if (!defined('ABSPATH')) {
    exit;
}

/* =====================================================
 * CONSTANTS
 * ===================================================== */

define('WARRANTY_PATH', plugin_dir_path(__FILE__));
define('WARRANTY_URL', plugin_dir_url(__FILE__));

/* =====================================================
 * LOAD CORE FILES
 * ===================================================== */

/* CPT */
require_once WARRANTY_PATH . 'includes/cpt-warranty.php';
require_once WARRANTY_PATH . 'includes/warranty-status.php';

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

/* =====================================================
 * ADMIN UI CLEANUP
 * ===================================================== */

/* Remove editor + title support */
add_action('admin_init', function () {
    remove_post_type_support('warranty', 'editor');
    remove_post_type_support('warranty', 'title');
});

/* Remove Publish box */
add_action('add_meta_boxes', function () {
    remove_meta_box('submitdiv', 'warranty', 'side');
}, 100);

/* =====================================================
 * ADMIN SCRIPTS & STYLES
 * ===================================================== */

add_action('admin_enqueue_scripts', function ($hook) {

    if (!in_array($hook, ['post-new.php', 'post.php'], true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'warranty') {
        return;
    }

    /* Admin JS */
    wp_enqueue_script(
        'warranty-admin-js',
        WARRANTY_URL . 'assets/admin/admin-warranty.js',
        ['jquery'],
        '2.1',
        true
    );

    /* Admin CSS (hides title safely) */
    wp_enqueue_style(
        'warranty-admin-css',
        WARRANTY_URL . 'assets/admin/admin-warranty.css',
        [],
        '2.1'
    );

    /* Fix wp-pointer dependency */
    wp_enqueue_script('wp-pointer');
    wp_enqueue_style('wp-pointer');
});

/* =====================================================
 * SAVE WARRANTY (SINGLE SOURCE OF TRUTH)
 * ===================================================== */

add_action('save_post_warranty', function ($post_id, $post) {

    if (empty($_POST['warranty_admin_submit'])) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    /* Save basic fields */
    update_post_meta($post_id, 'email', sanitize_email($_POST['email'] ?? ''));
    update_post_meta($post_id, 'phone', sanitize_text_field($_POST['phone'] ?? ''));
    update_post_meta($post_id, 'purchase_date', sanitize_text_field($_POST['purchase_date'] ?? ''));

    /* Save products (multi-quantity) */
    if (!empty($_POST['products']) && is_array($_POST['products'])) {
        $products = [];

        foreach ($_POST['products'] as $p) {
            $products[] = [
                'model' => sanitize_text_field($p['model'] ?? ''),
                'size'  => sanitize_text_field($p['size'] ?? ''),
            ];
        }

        update_post_meta($post_id, 'products', $products);
    }

    /* Auto-generate internal title (no UI usage) */
    wp_update_post([
        'ID'         => $post_id,
        'post_title' => 'Warranty – ' . sanitize_email($_POST['email'] ?? 'Unknown'),
    ]);

}, 10, 2);

/* =====================================================
 * REDIRECT AFTER SAVE
 * ===================================================== */

add_filter('redirect_post_location', function ($location, $post_id) {

    if (
        get_post_type($post_id) === 'warranty' &&
        !empty($_POST['warranty_admin_submit'])
    ) {
        return admin_url('edit.php?post_type=warranty');
    }

    return $location;
}, 10, 2);