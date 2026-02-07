<?php
if (!defined('ABSPATH')) exit;

add_filter('manage_warranty_posts_columns', function () {
    return [
        'cb' => 'cb',
        'title' => 'Order',
        'email' => 'Email',
        'products' => 'Products',
        'date' => 'Submitted'
    ];
});

add_action('manage_warranty_posts_custom_column', function ($col, $id) {
    if ($col === 'email') {
        echo esc_html(get_post_meta($id, 'email', true));
    }
    if ($col === 'products') {
        $products = get_post_meta($id, 'products', true);
        foreach ($products as $p) {
            echo esc_html($p['model'].' ('.$p['qty'].')') . '<br>';
        }
    }
}, 10, 2);