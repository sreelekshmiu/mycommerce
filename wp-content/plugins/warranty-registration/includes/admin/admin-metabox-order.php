<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'warranty_order',
        'WooCommerce Order',
        'warranty_order_box',
        'warranty',
        'normal',
        'high'
    );
});


function warranty_order_box($post) {
?>
  <input type="number" id="warranty_order_id" placeholder="Order ID">
  <button type="button" class="button" id="load_warranty_order">Load Order</button>

  <p style="margin-top:10px;color:#666;">
    Products will be loaded into the Warranty Details section below.
  </p>
<?php
}