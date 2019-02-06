<?php
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

function {%= function_prefix %}register_post_types(){

    register_post_type( 'custom_post', array(
        'label'              => 'Custom Post',
        'hierarchical'        => false,
        'supports'            => array( 'title' ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'menu_position'       => 5,
        'menu_icon'           => '',
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => true,
        'capability_type'     => 'post',
    ) );
}

add_action( 'init', '{%= function_prefix %}register_post_types' );

function {%= function_prefix %}register_taxonomies(){
    register_taxonomy( 'custom_tax', array( 'custom_post' ), array(
        'hierarchical'      => true,
        'label'            => 'Tax',
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    ) ); 
}
add_action( 'init', '{%= function_prefix %}register_taxonomies' );
