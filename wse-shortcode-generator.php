<?php
/*
Plugin Name: WSE Shortcode Generator
Description: A shortcode generator plugin
Version: 1.0
Author: WP Shopify Expert
Author URI: https://wpshopifyexpert.com
Text Domain: wse-shortcode-generator
*/

function wse_shortcode_generator_shortcode($atts) {
    // Sanitize the ID
    $id = sanitize_key($atts['id']);

    // Get the post content
    $post = get_post($id);
    $content = $post->post_content;

    // Parse blocks and render
    $blocks = parse_blocks($content);
    $output = '';
    foreach ($blocks as $block) {
        $output .= render_block($block);
    }

    return $output;
}

add_shortcode('wse_shortcode_generator', 'wse_shortcode_generator_shortcode');



// Create custom post type
function wse_create_custom_post_type() {
    register_post_type( 'wse_shortcode',
        array(
            'labels' => array(
                'name_admin_bar' => __( 'WSE Shortcode Generator', 'wse-shortcode-generator' ),
                'name' => __( 'WSE Shortcode Generators', 'wse-shortcode-generator' ),
                'singular_name' => __( 'WSE Shortcode Generator', 'wse-shortcode-generator' ),
                'menu_name' => __( 'WSE Shortcode Generators', 'wse-shortcode-generator' ),
                'all_items' => __( 'All ', 'wse-shortcode-generator' ),
                'add_new' => __( 'Add New ', 'wse-shortcode-generator' ),
                'add_new_item' => __( 'Add New', 'wse-shortcode-generator' ),
                'edit_item' => __( 'Edit', 'wse-shortcode-generator' ),
                'new_item' => __( 'New', 'wse-shortcode-generator' ),
                'view_item' => __( 'View', 'wse-shortcode-generator' ),
                'search_items' => __( 'Searchs', 'wse-shortcode-generator' ),
                'not_found' => __( 'No WSE Shortcode Generators found', 'wse-shortcode-generator' ),
                'not_found_in_trash' => __( 'No WSE Shortcode Generators found in trash', 'wse-shortcode-generator' ),
            ),
           
            'public'                => false,
            'show_ui'               => true, 		
            'show_in_rest'          => true,							
            'publicly_queryable'    => false,
            'exclude_from_search'   => true,
            'has_archive' => false,
            'supports' => array( 'title', 'editor' ),
            'menu_icon' => 'dashicons-admin-page',
            'menu_position' => 20,
            'hierarchical'          => false,
            'capability_type'       => 'page',
            'rewrite'               => array( 'slug' => "wse_shortcode" )
        )
    );
}
add_action( 'init', 'wse_create_custom_post_type' );


// Enable Gutenberg editor for specific post types
function enable_gutenberg_editor_for_post_types( $current_status, $post_type ) {
    // Define an array of post types where Gutenberg should be enabled
    $enabled_post_types = array( 'wse_shortcode' );

    // Check if the current post type is in the enabled post types array
    if ( in_array( $post_type, $enabled_post_types ) ) {
        return true; // Enable Gutenberg editor
    }

    return $current_status; // Keep current editor status
}
add_filter( 'use_block_editor_for_post_type', 'enable_gutenberg_editor_for_post_types', 10, 2 );




function wse_add_column_header($columns) {
    $columns['wse_shortcode_column'] = __('Shortcode', 'wse-shortcode-generator');
    unset($columns['date']);
    $columns['date'] = __('Date', 'wse-shortcode-generator');
    return $columns;
}
add_filter('manage_wse_shortcode_posts_columns', 'wse_add_column_header');

function wse_add_column_content($column, $post_id) {
    if ($column == 'wse_shortcode_column') {
        $shortcode_content = '[wse_shortcode_generator id="' . $post_id . '"]';
        echo $shortcode_content;
    }
}
add_action('manage_wse_shortcode_posts_custom_column', 'wse_add_column_content', 10, 2);