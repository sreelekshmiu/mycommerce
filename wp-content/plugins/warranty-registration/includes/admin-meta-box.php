<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {

    add_meta_box(
        'warranty_source',
        'Warranty Source (Order / Customer)',
        'render_warranty_source_box',
        'warranty',
        'side',          // 👈 shows clearly on right side
        'high'
    );
});

function render_warranty_source_box($post) {

    $order_id = get_post_meta($post->ID, 'order_id', true);
    $user_id  = get_post_meta($post->ID, 'user_id', true);
    ?>

    <p>
        <label><strong>WooCommerce Order ID</strong></label>
        <input type="number" name="order_id" value="<?php echo esc_attr($order_id); ?>" style="width:100%;" />
        <small>Enter Order ID and Publish / Update</small>
    </p>

    <hr>

    <p>
        <label><strong>OR Select Customer</strong></label>
        <?php
        wp_dropdown_users([
            'name'             => 'user_id',
            'selected'         => $user_id,
            'show_option_none' => 'Select Customer',
        ]);
        ?>
    </p>
    <?php
}
add_action('save_post_warranty', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $customer = get_post_meta($post_id, 'customer_name', true);

    if ($customer) {
        remove_action('save_post_warranty', __FUNCTION__);
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $customer
        ]);
        add_action('save_post_warranty', __FUNCTION__);
    }

}, 20);

