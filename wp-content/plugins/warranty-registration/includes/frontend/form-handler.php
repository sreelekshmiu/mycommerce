<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {

    if (empty($_POST['submit_warranty'])) return;
    if (!wp_verify_nonce($_POST['warranty_nonce'], 'warranty_nonce')) return;

    $post_id = wp_insert_post([
        'post_type'   => 'warranty',
        'post_status' => 'publish',
        'post_title'  => 'Warranty – Order #' . sanitize_text_field($_POST['order_id']),
    ]);

    update_post_meta($post_id, 'order_id', sanitize_text_field($_POST['order_id']));
    update_post_meta($post_id, 'email', sanitize_email($_POST['email']));
    update_post_meta($post_id, 'phone', sanitize_text_field($_POST['phone']));
    update_post_meta($post_id, 'purchase_date', sanitize_text_field($_POST['purchase_date']));
    update_post_meta($post_id, 'activation_date', sanitize_text_field($_POST['activation_date']));

    // Products
    $products = [];
    foreach ($_POST['products'] as $p) {
        $products[] = [
            'model' => sanitize_text_field($p['model']),
            'size'  => sanitize_text_field($p['size']),
            'qty'   => intval($p['qty']),
        ];
    }
    update_post_meta($post_id, 'products', $products);

    // Upload proof
    if (!empty($_FILES['purchase_proof']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $file = wp_handle_upload($_FILES['purchase_proof'], ['test_form' => false]);
        update_post_meta($post_id, 'proof_url', $file['url']);
    }

    wp_redirect(add_query_arg('warranty_success', '1', wp_get_referer()));
    exit;
});