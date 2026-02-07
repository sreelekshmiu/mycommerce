
 <?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {

    if (!isset($_POST['submit_warranty'])) return;

    foreach ($_POST['products'] as $product) {

        $post_id = wp_insert_post([
            'post_type' => 'warranty',
            'post_status' => 'publish',
            'post_title' => sanitize_text_field($product['model']),
        ]);

        update_post_meta($post_id, 'email', sanitize_email($_POST['email']));
        update_post_meta($post_id, 'model', sanitize_text_field($product['model']));
        update_post_meta($post_id, 'size', sanitize_text_field($product['size']));
    }

    wp_redirect(add_query_arg('success', '1', wp_get_referer()));
    exit;
});