<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_load_warranty_order', function () {

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    if (!class_exists('WooCommerce')) {
        wp_send_json_error('WooCommerce not active');
    }

    $order_id = absint($_POST['order_id'] ?? 0);
    if (!$order_id) {
        wp_send_json_error('Invalid Order ID');
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error('Order not found');
    }

    $items = [];

    foreach ($order->get_items() as $item) {

        $product = $item->get_product();
        if (!$product) {
            continue;
        }

        // Get readable variation attributes
        $size = '';

        if ($product->is_type('variation')) {
            $attributes = $product->get_attributes();
            $values = [];

            foreach ($attributes as $attr => $value) {
                $values[] = wc_attribute_label($attr) . ': ' . $value;
            }

            $size = implode(', ', $values);
        }

        // Quantity handling
        $qty = max(1, (int) $item->get_quantity());

        for ($i = 0; $i < $qty; $i++) {
            $items[] = [
                'model' => $product->get_name(),
                'size'  => $size,
            ];
        }
    }

    wp_send_json_success([
        'email' => $order->get_billing_email(),
        'phone' => $order->get_billing_phone(),
        'purchase_date' => $order->get_date_created()
            ? $order->get_date_created()->date('Y-m-d')
            : '',
        'items' => $items,
    ]);
});