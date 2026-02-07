<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_load_warranty_order', 'load_warranty_order');
add_action('wp_ajax_nopriv_load_warranty_order', 'load_warranty_order');

function load_warranty_order() {

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
        if (!$product) continue;

        $qty = max(1, (int) $item->get_quantity());
        for ($i = 0; $i < $qty; $i++) {
            $items[] = [
                'model' => $product->get_name(),
                'size'  => '',
            ];
        }
    }

    wp_send_json_success([
        'email'         => $order->get_billing_email(),
        'phone'         => $order->get_billing_phone(),
        'purchase_date'=> $order->get_date_created()
            ? $order->get_date_created()->date('Y-m-d')
            : '',
        'items'         => $items,
    ]);
}