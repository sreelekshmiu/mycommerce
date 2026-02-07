<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {

    register_post_type('warranty', [
        'labels' => [
            'name'                  => 'Warranties',
            'singular_name'         => 'Warranty',
            'menu_name'             => 'Warranties',
            'name_admin_bar'        => 'Warranty',
            'add_new'               => 'Add Warranty',
            'add_new_item'          => 'Add Warranty',
            'edit_item'             => 'Edit Warranty',
            'new_item'              => 'New Warranty',
            'view_item'             => 'View Warranty',
            'search_items'          => 'Search Warranties',
            'not_found'             => 'No warranties found',
            'not_found_in_trash'    => 'No warranties found in Trash',
            'all_items'             => 'All Warranties',
        ],

        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_admin_bar'  => true,
        'menu_icon'          => 'dashicons-shield',
        'supports'           => [], // NO title, NO editor
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ]);
});