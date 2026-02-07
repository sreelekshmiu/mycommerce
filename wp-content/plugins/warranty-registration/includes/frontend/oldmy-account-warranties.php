<?php
if (!defined('ABSPATH')) exit;

add_action('woocommerce_account_my-warranties_endpoint', function () {

    $user = wp_get_current_user();

    $q = new WP_Query([
        'post_type' => 'warranty',
        'meta_key' => 'email',
        'meta_value' => $user->user_email
    ]);

    echo '<table>';
    while ($q->have_posts()) {
        $q->the_post();
        echo '<tr>
            <td>'.get_post_meta(get_the_ID(),'model',true).'</td>
            <td>'.get_post_meta(get_the_ID(),'size',true).'</td>
        </tr>';
    }
    echo '</table>';
});