<?php
if (!defined('ABSPATH')) exit;

/**
 * Warranty Details Meta Box
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'warranty_details',
        'Warranty Details',
        'warranty_details_box',
        'warranty',
        'normal',
        'high'
    );
});

function warranty_details_box($post) {

    // Pre-fill values when editing
    $email         = get_post_meta($post->ID, 'email', true);
    $phone         = get_post_meta($post->ID, 'phone', true);
    $purchase_date = get_post_meta($post->ID, 'purchase_date', true);
    ?>
    <div class="warranty-admin-form">

        <p>
            <label><strong>Email</strong></label><br>
            <input
                type="email"
                name="email"
                value="<?php echo esc_attr($email); ?>"
                required
                style="width:300px;"
            >
        </p>

        <p>
            <label><strong>Phone</strong></label><br>
            <input
                type="text"
                name="phone"
                value="<?php echo esc_attr($phone); ?>"
                style="width:300px;"
            >
        </p>

        <p>
            <label><strong>Purchase Date</strong></label><br>
            <input
                type="date"
                name="purchase_date"
                value="<?php echo esc_attr($purchase_date); ?>"
            >
        </p>
        <h4 style="margin-top:20px;">Products</h4>
<table class="widefat">
    <thead>
        <tr>
            <th>Model</th>
            <th>Size</th>
        </tr>
    </thead>
    <tbody id="warranty-products"></tbody>
</table>

         <!--Products populated dynamically by admin-warranty.js -->
        <!--<h4 style="margin-top:20px;">Products</h4>-->
        <!--<table class="widefat">-->
        <!--    <thead>-->
        <!--        <tr>-->
        <!--            <th>Model</th>-->
        <!--            <th>Size</th>-->
        <!--        </tr>-->
        <!--    </thead>-->
        <!--    <tbody id="warranty-products">-->
        <!--         JS inserts product rows here -->
        <!--    </tbody>-->
        <!--</table>-->

        <!-- 🔑 CRITICAL FLAG (DO NOT REMOVE) -->
        <input type="hidden" name="warranty_admin_submit" value="1">

        <p style="margin-top:20px;">
            <button type="submit" class="button button-primary button-large">
                Submit Warranty
            </button>
        </p>

    </div>
    <?php
}