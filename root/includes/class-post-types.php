<?php

namespace Pluginever\{%= class_name %};

class PostTypes {
    /**
     * PostTypes constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        register_post_type( 'custom_post', array(
            'labels'              => $this->get_posts_labels( 'Custom Post', __( 'Custom Post', '{%= text_domain %}' ), __( 'Custom Posts', '{%= text_domain %}' ) ),
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

	/**
	 * Register custom taxonomies
	 *
	 * @since 1.0.0
	 */
    public function register_taxonomies() {
        register_taxonomy( 'custom_tax', array( 'custom_post' ), array(
            'hierarchical'      => true,
            'labels'            => $this->get_posts_labels( 'Custom Tax', __( 'Custom Tax', '{%= text_domain %}' ), __( 'Custom Taxs', '{%= text_domain %}' ) ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'genre' ),
        ) );

    }

	/**
	 * Get all labels from post types
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
    protected static function get_posts_labels( $menu_name, $singular, $plural ) {
        $labels = array(
            'name'               => $singular,
            'all_items'          => sprintf( __( "All %s", '{%= text_domain %}' ), $plural ),
            'singular_name'      => $singular,
            'add_new'            => sprintf( __( 'New %s', '{%= text_domain %}' ), $singular ),
            'add_new_item'       => sprintf( __( 'Add New %s', '{%= text_domain %}' ), $singular ),
            'edit_item'          => sprintf( __( 'Edit %s', '{%= text_domain %}' ), $singular ),
            'new_item'           => sprintf( __( 'New %s', '{%= text_domain %}' ), $singular ),
            'view_item'          => sprintf( __( 'View %s', '{%= text_domain %}' ), $singular ),
            'search_items'       => sprintf( __( 'Search %s', '{%= text_domain %}' ), $plural ),
            'not_found'          => sprintf( __( 'No %s found', '{%= text_domain %}' ), $plural ),
            'not_found_in_trash' => sprintf( __( 'No %s found in Trash', '{%= text_domain %}' ), $plural ),
            'parent_item_colon'  => sprintf( __( 'Parent %s:', '{%= text_domain %}' ), $singular ),
            'menu_name'          => $menu_name,
        );

        return $labels;
    }

	/**
	 * Get all labels from taxonomies
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
    protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
        $labels = array(
            'name'              => sprintf( _x( '%s', 'taxonomy general name', '{%= text_domain %}' ), $plural ),
            'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', '{%= text_domain %}' ), $singular ),
            'search_items'      => sprintf( __( 'Search %', '{%= text_domain %}' ), $plural ),
            'all_items'         => sprintf( __( 'All %s', '{%= text_domain %}' ), $plural ),
            'parent_item'       => sprintf( __( 'Parent %s', '{%= text_domain %}' ), $singular ),
            'parent_item_colon' => sprintf( __( 'Parent %s:', '{%= text_domain %}' ), $singular ),
            'edit_item'         => sprintf( __( 'Edit %s', '{%= text_domain %}' ), $singular ),
            'update_item'       => sprintf( __( 'Update %s', '{%= text_domain %}' ), $singular ),
            'add_new_item'      => sprintf( __( 'Add New %s', '{%= text_domain %}' ), $singular ),
            'new_item_name'     => sprintf( __( 'New % Name', '{%= text_domain %}' ), $singular ),
            'menu_name'         => __( $menu_name, '{%= text_domain %}' ),
        );

        return $labels;
    }
}
