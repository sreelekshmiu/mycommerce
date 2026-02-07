<?php
if (!defined('ABSPATH')) exit;
add_shortcode('warranty_lookup', function () {

    if (empty($_GET['email']) || empty($_GET['order_id'])) {
        return '<p>Please enter email & order ID.</p>';
    }

    $q = new WP_Query([
        'post_type' => 'warranty',
        'meta_query' => [
            ['key' => 'email', 'value' => sanitize_email($_GET['email'])],
            ['key' => 'order_id', 'value' => sanitize_text_field($_GET['order_id'])]
        ]
    ]);

    if (!$q->have_posts()) return 'No warranty found';

    ob_start();
    while ($q->have_posts()) {
        $q->the_post();
        echo '<h4>'.get_the_title().'</h4>';
    }
    return ob_get_clean();
});