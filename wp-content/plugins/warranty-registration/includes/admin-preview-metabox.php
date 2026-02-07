<?php
if (!defined('ABSPATH')) exit;

/**
 * Warranty Preview Meta Box (Admin)
 */
add_action('add_meta_boxes', function () {

    add_meta_box(
        'warranty_preview_box',
        'Warranty Preview (Read Only)',
        'render_warranty_preview_box',
        'warranty',
        'normal',
        'high'
    );
});

function render_warranty_preview_box($post) {

    $fields = [
        'Customer Name'  => trim(
            get_post_meta($post->ID, 'first_name', true) . ' ' .
            get_post_meta($post->ID, 'last_name', true)
        ),
        'Email'          => get_post_meta($post->ID, 'email', true),
        'Phone'          => get_post_meta($post->ID, 'phone', true),
        'Address'        => implode(', ', array_filter([
            get_post_meta($post->ID, 'address_1', true),
            get_post_meta($post->ID, 'address_2', true),
            get_post_meta($post->ID, 'city', true),
            get_post_meta($post->ID, 'state', true),
            get_post_meta($post->ID, 'zip', true),
            get_post_meta($post->ID, 'country', true),
        ])),
        'Model'          => get_post_meta($post->ID, 'model', true),
        'Size'           => get_post_meta($post->ID, 'size', true),
        'Purchase Date'  => get_post_meta($post->ID, 'purchase_date', true),
        'Activation Date'=> get_post_meta($post->ID, 'activation_date', true),
        'Order ID'       => get_post_meta($post->ID, 'order_id', true),
        'Proof'          => get_post_meta($post->ID, 'proof_url', true),
    ];

    echo '<table class="widefat striped">';
    foreach ($fields as $label => $value) {

        if (!$value) {
            $value = '<em>Not available</em>';
        } elseif ($label === 'Proof') {
            $value = '<a href="' . esc_url($value) . '" target="_blank">View Proof</a>';
        } else {
            $value = esc_html($value);
        }

        echo '<tr>
                <th style="width:180px;">' . esc_html($label) . '</th>
                <td>' . $value . '</td>
              </tr>';
    }
    echo '</table>';
}
